<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $mess_id
 * @property string $date
 * @property int $total_meal
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mess $mess
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyMeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyMeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyMeal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyMeal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyMeal whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyMeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyMeal whereMessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyMeal whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyMeal whereTotalMeal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyMeal whereUpdatedAt($value)
 */
	class DailyMeal extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $mess_id
 * @property int $user_id
 * @property string $date
 * @property string $amount
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mess $mess
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereMessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUserId($value)
 */
	class Expense extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $mess_id
 * @property int $user_id
 * @property string $date
 * @property int $meal_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mess $mess
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberMeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberMeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberMeal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberMeal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberMeal whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberMeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberMeal whereMealCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberMeal whereMessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberMeal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberMeal whereUserId($value)
 */
	class MemberMeal extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $location
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $owner_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DailyMeal> $dailyMeals
 * @property-read int|null $daily_meals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
 * @property-read int|null $members_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mess query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mess whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mess whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mess whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mess whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mess whereUpdatedAt($value)
 */
	class Mess extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $mess_id
 * @property int $user_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mess $mess
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessJoinRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessJoinRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessJoinRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessJoinRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessJoinRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessJoinRequest whereMessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessJoinRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessJoinRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MessJoinRequest whereUserId($value)
 */
	class MessJoinRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mess[] $memberships
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $memberships_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mess> $messes
 * @property-read int|null $messes_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

