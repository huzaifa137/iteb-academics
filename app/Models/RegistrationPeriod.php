<?php
namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RegistrationPeriod extends Model
{
    protected $table = 'registration_periods';
    protected $fillable = ['name','admission_year','opens_at','closes_at','is_active','status','created_by'];
    protected $casts = ['opens_at'=>'datetime','closes_at'=>'datetime','is_active'=>'boolean'];

    const STATUS_ACTIVE   = 'active';
    const STATUS_CLOSED   = 'closed';
    const STATUS_ARCHIVED = 'archived';

    const STATUSES = [self::STATUS_ACTIVE, self::STATUS_CLOSED, self::STATUS_ARCHIVED];

    public static function active(): ?self {
        return self::where('status', self::STATUS_ACTIVE)->first();
    }

    public static function globallyOpen(): bool {
        $period = self::active();
        if (!$period) return false;
        $now = Carbon::now();
        if ($period->opens_at && $now->lt($period->opens_at)) return false;
        if ($period->closes_at && $now->gt($period->closes_at)) return false;
        return true;
    }

    /**
     * Activate this period and automatically close any other currently active period.
     * Records are never deleted here - this only flips status so history/re-activation stays possible.
     */
    public function activate(): void
    {
        self::where('id', '!=', $this->id)
            ->where('status', self::STATUS_ACTIVE)
            ->update(['status' => self::STATUS_CLOSED, 'is_active' => false]);

        $this->status = self::STATUS_ACTIVE;
        $this->is_active = true;
        $this->save();
    }

    public function close(): void
    {
        $this->status = self::STATUS_CLOSED;
        $this->is_active = false;
        $this->save();
    }

    public function archive(): void
    {
        $this->status = self::STATUS_ARCHIVED;
        $this->is_active = false;
        $this->save();
    }
}