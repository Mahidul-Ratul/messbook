import subprocess
import time

# ==========================================
#               CONFIGURATION
# ==========================================
# Core microservices managed under the auto-scaling policy
SERVICES_TO_MONITOR = ["member-service", "billing-service", "admin-service", "owner-service"]

# Step Scaling Thresholds (CPU %)
CPU_CRITICAL_THRESHOLD = 85.0  # Scale immediately to max capacity
CPU_HIGH_THRESHOLD     = 65.0  # Scale to high capacity
CPU_MODERATE_THRESHOLD = 40.0  # Scale to moderate capacity
CPU_LOW_THRESHOLD      = 15.0  # Scale back down to baseline

# Replication Step Sizes
REPLICAS_BASE     = 1
REPLICAS_MODERATE = 2
REPLICAS_HIGH     = 3
REPLICAS_CRITICAL = 4

# Tracks the active horizontal scale state of each service in memory
current_state = {service: REPLICAS_BASE for service in SERVICES_TO_MONITOR}

# ==========================================
#             CORE UTILITIES
# ==========================================
def get_cpu_usage(service_name):
    """Polls the Docker Daemon API to fetch live container CPU consumption."""
    try:
        container_name = f"messbook-{service_name}-1"
        cmd = ["docker", "stats", "--no-stream", "--format", "{{.CPUPerc}}", container_name]
        result = subprocess.run(cmd, capture_output=True, text=True)
        usage_str = result.stdout.replace('%', '').strip()
        return float(usage_str) if usage_str else 0.0
    except Exception:
        return 0.0

def scale_service(service_name, target_count):
    """Executes orchestration command to match targeted horizontal scale."""
    print(f"\n🚀 [CLOUD ACTION] Scaling {service_name} to {target_count} instances... 🚀")
    subprocess.run(["docker-compose", "up", "-d", "--scale", f"{service_name}={target_count}"])
    current_state[service_name] = target_count

# ==========================================
#               MAIN DEVOPS LOOP
# ==========================================
print(f"--- MessBook Universal Step-Scaling Auto-Scaler Active ---")
print(f"Monitoring: {', '.join(SERVICES_TO_MONITOR)}")
print(f"Policies: >85% (4x) | >65% (3x) | >40% (2x) | <15% (1x)\n" + "="*60 + "\n")

while True:
    triggered_cooldown = False

    for service in SERVICES_TO_MONITOR:
        cpu = get_cpu_usage(service)
        current_replicas = current_state[service]
        
        print(f"[{service}] CPU Load: {cpu:6.2f}% | Active Replicas: {current_replicas}")

        # --- 1. CRITICAL EMERGENCY STEP (>85% CPU) ---
        if cpu > CPU_CRITICAL_THRESHOLD:
            if current_replicas != REPLICAS_CRITICAL:
                print(f"🚨🚨🚨 CRITICAL OVERLOAD ON {service.upper()} ({cpu}%)! Maxing out cluster.")
                scale_service(service, REPLICAS_CRITICAL)
                triggered_cooldown = True

        # --- 2. HIGH LOAD STEP (>65% CPU) ---
        elif cpu > CPU_HIGH_THRESHOLD:
            if current_replicas < REPLICAS_HIGH:
                print(f"🔥 HIGH TRAFFIC BURST ON {service.upper()} ({cpu}%)! Scaling to step 3.")
                scale_service(service, REPLICAS_HIGH)
                triggered_cooldown = True

        # --- 3. MODERATE LOAD STEP (>40% CPU) ---
        elif cpu > CPU_MODERATE_THRESHOLD:
            if current_replicas < REPLICAS_MODERATE:
                print(f"📈 MODERATE LOAD INCREASE ON {service.upper()} ({cpu}%)! Scaling to step 2.")
                scale_service(service, REPLICAS_MODERATE)
                triggered_cooldown = True

        # --- 4. AUTOMATIC SCALE-DOWN STEP (<15% CPU) ---
        elif cpu < CPU_LOW_THRESHOLD:
            if current_replicas > REPLICAS_BASE:
                print(f"❄️ TRAFFIC COOLED DOWN ON {service.upper()} ({cpu}%)! De-provisioning idle containers.")
                scale_service(service, REPLICAS_BASE)
                triggered_cooldown = True

    # Cooldown verification window to prevent infrastructure "flapping"
    if triggered_cooldown:
        print("\n⏳ Infrastructure adapting. Activating 30-second metric stabilization window... ⏳\n")
        time.sleep(30)
    else:
        print("-" * 60)
        time.sleep(5)  # Nominal sampling frequency (every 5 seconds)