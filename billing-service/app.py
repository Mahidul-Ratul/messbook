import calendar
import os
import re
import time
from datetime import date, datetime
from decimal import Decimal

import mysql.connector
from flask import Flask, jsonify, render_template, request

app = Flask(__name__)

MONTH_PATTERN = re.compile(r"^\d{4}-\d{2}$")


def get_db_connection():
    last_error = None

    for _ in range(5):
        try:
            return mysql.connector.connect(
                host=os.getenv("DB_HOST", "db"),
                port=int(os.getenv("DB_PORT", "3306")),
                user=os.getenv("DB_USER", "root"),
                password=os.getenv("DB_PASSWORD", "root"),
                database=os.getenv("DB_NAME", "messbook_db"),
                connect_timeout=10,
            )
        except mysql.connector.Error as exc:
            last_error = exc
            time.sleep(2)

    raise last_error


def month_bounds(month_value):
    if not month_value or not MONTH_PATTERN.match(month_value):
        month_value = datetime.utcnow().strftime("%Y-%m")

    year, month = map(int, month_value.split("-"))
    last_day = calendar.monthrange(year, month)[1]
    start_date = date(year, month, 1)
    end_date = date(year, month, last_day)
    label = datetime(year, month, 1).strftime("%B %Y")
    return month_value, start_date, end_date, label


def decimal_to_native(value):
    if isinstance(value, Decimal):
        return float(value)
    if isinstance(value, (date, datetime)):
        return value.isoformat()
    if isinstance(value, list):
        return [decimal_to_native(item) for item in value]
    if isinstance(value, dict):
        return {key: decimal_to_native(item) for key, item in value.items()}
    return value


def query_one(cursor, query, params=()):
    cursor.execute(query, params)
    return cursor.fetchone()


def query_all(cursor, query, params=()):
    cursor.execute(query, params)
    return cursor.fetchall()


def load_member_options(cursor):
    return query_all(
        cursor,
        """
        SELECT DISTINCT u.id, u.name, u.email
        FROM users u
        INNER JOIN mess_members mm ON mm.user_id = u.id
        ORDER BY u.name ASC, u.id ASC
        """,
    )


def load_mess_options(cursor):
    return query_all(
        cursor,
        """
        SELECT mm.mess_id, COUNT(*) AS member_count
        FROM mess_members mm
        GROUP BY mm.mess_id
        ORDER BY mm.mess_id ASC
        """,
    )


def build_bill_data(member_id, mess_id=None, month=None):
    month_value, start_date, end_date, month_label = month_bounds(month)
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    try:
        member = query_one(
            cursor,
            "SELECT id, name, email FROM users WHERE id = %s",
            (member_id,),
        )

        if not member:
            raise ValueError(f"Member {member_id} was not found.")

        memberships = query_all(
            cursor,
            """
            SELECT mm.mess_id, mm.created_at
            FROM mess_members mm
            WHERE mm.user_id = %s
            ORDER BY mm.created_at DESC, mm.id DESC
            """,
            (member_id,),
        )

        membership_ids = [row["mess_id"] for row in memberships]
        selected_mess_id = mess_id or (membership_ids[0] if membership_ids else None)

        if selected_mess_id is None:
            raise ValueError("This member is not attached to any mess yet.")

        if selected_mess_id not in membership_ids:
            raise ValueError(f"Member {member_id} is not part of mess {selected_mess_id}.")

        member_count_row = query_one(
            cursor,
            "SELECT COUNT(*) AS member_count FROM mess_members WHERE mess_id = %s",
            (selected_mess_id,),
        )

        total_meals_row = query_one(
            cursor,
            """
            SELECT COALESCE(SUM(total_meal), 0) AS total_meals
            FROM daily_meals
            WHERE mess_id = %s
              AND date BETWEEN %s AND %s
            """,
            (selected_mess_id, start_date, end_date),
        )

        total_expenses_row = query_one(
            cursor,
            """
            SELECT COALESCE(SUM(amount), 0) AS total_expenses
            FROM expenses
            WHERE mess_id = %s
              AND date BETWEEN %s AND %s
            """,
            (selected_mess_id, start_date, end_date),
        )

        member_meals_row = query_one(
            cursor,
            """
            SELECT COALESCE(SUM(meal_count), 0) AS member_meals
            FROM member_meals
            WHERE mess_id = %s
              AND user_id = %s
              AND date BETWEEN %s AND %s
            """,
            (selected_mess_id, member_id, start_date, end_date),
        )

        member_expenses_row = query_one(
            cursor,
            """
            SELECT COALESCE(SUM(amount), 0) AS member_expenses
            FROM expenses
            WHERE mess_id = %s
              AND user_id = %s
              AND date BETWEEN %s AND %s
            """,
            (selected_mess_id, member_id, start_date, end_date),
        )

        daily_meals = query_all(
            cursor,
            """
            SELECT date, total_meal, notes
            FROM daily_meals
            WHERE mess_id = %s
              AND date BETWEEN %s AND %s
            ORDER BY date DESC, id DESC
            LIMIT 12
            """,
            (selected_mess_id, start_date, end_date),
        )

        member_meal_rows = query_all(
            cursor,
            """
            SELECT date, meal_count
            FROM member_meals
            WHERE mess_id = %s
              AND user_id = %s
              AND date BETWEEN %s AND %s
            ORDER BY date DESC, id DESC
            LIMIT 12
            """,
            (selected_mess_id, member_id, start_date, end_date),
        )

        member_expense_rows = query_all(
            cursor,
            """
            SELECT date, amount, description
            FROM expenses
            WHERE mess_id = %s
              AND user_id = %s
              AND date BETWEEN %s AND %s
            ORDER BY date DESC, id DESC
            LIMIT 12
            """,
            (selected_mess_id, member_id, start_date, end_date),
        )

        member_meals_total = member_meals_row["member_meals"] or 0
        total_meals = total_meals_row["total_meals"] or 0
        total_expenses = total_expenses_row["total_expenses"] or 0
        member_expenses = member_expenses_row["member_expenses"] or 0

        meal_rate = float(total_expenses) / float(total_meals) if total_meals else 0.0
        bill_amount = float(member_meals_total) * meal_rate
        balance = float(member_expenses) - bill_amount

        return {
            "member": member,
            "mess_id": selected_mess_id,
            "mess_label": f"Mess #{selected_mess_id}",
            "month": month_value,
            "month_label": month_label,
            "range": {
                "start": start_date.isoformat(),
                "end": end_date.isoformat(),
            },
            "summary": {
                "member_count": member_count_row["member_count"] or 0,
                "total_meals": float(total_meals),
                "total_expenses": float(total_expenses),
                "meal_rate": round(meal_rate, 2),
                "member_meals": float(member_meals_total),
                "member_expenses": float(member_expenses),
                "bill_amount": round(bill_amount, 2),
                "balance": round(balance, 2),
            },
            "available_memberships": memberships,
            "daily_meals": daily_meals,
            "member_meal_rows": member_meal_rows,
            "member_expense_rows": member_expense_rows,
        }
    finally:
        cursor.close()
        conn.close()


def load_page_options():
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    try:
        return {
            "members": load_member_options(cursor),
            "messes": load_mess_options(cursor),
        }
    finally:
        cursor.close()
        conn.close()


@app.route("/")
def home():
    member_id = request.args.get("member_id", type=int)
    mess_id = request.args.get("mess_id", type=int)
    month = request.args.get("month")

    options = load_page_options()
    bill_data = None
    error = None

    if member_id:
        try:
            bill_data = build_bill_data(member_id, mess_id=mess_id, month=month)
        except Exception as exc:
            error = str(exc)

    return render_template(
        "index.html",
        service_status="Billing Service is Online",
        member_id=member_id,
        mess_id=mess_id,
        month=month or datetime.utcnow().strftime("%Y-%m"),
        bill_data=bill_data,
        error=error,
        members=options["members"],
        messes=options["messes"],
    )


@app.route("/api/health")
def api_health():
    return jsonify({"status": "ok", "service": "billing-service"})


@app.route("/api/bill/<int:member_id>")
@app.route("/calculate-bill/<int:member_id>")
def calculate_bill(member_id):
    try:
        bill_data = build_bill_data(
            member_id,
            mess_id=request.args.get("mess_id", type=int),
            month=request.args.get("month"),
        )

        payload = decimal_to_native(
            {
                "status": "Success",
                "provider": "Python-Microservice",
                "member_id": member_id,
                "bill": bill_data,
            }
        )
        return jsonify(payload)
    except ValueError as exc:
        return jsonify({"status": "Error", "message": str(exc)}), 400
    except Exception as exc:
        return jsonify({"status": "Error", "message": str(exc)}), 500


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
