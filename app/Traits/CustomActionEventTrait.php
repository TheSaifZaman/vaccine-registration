<?php

namespace App\Traits;

trait CustomActionEventTrait
{
    /**
     * @var string[][][]
     */
    protected array $userActions = [
        'created_by' => ['depends_on_event' => ['creating']],
        'updated_by' => ['depends_on_event' => ['updating', 'saving']],
        'deleted_by' => ['depends_on_event' => ['deleting']]
    ];

    // events to capture
    protected static string $creating = 'creating';
    protected static string $saving = 'saving';
    protected static string $updating = 'updating';
    protected static string $deleting = 'deleting';

    /**
     * @return void
     */
    public static function bootCustomActionEventTrait(): void
    {
        $self = new static();

        static::creating(function ($model) use ($self) {
            $self->setUserEventOnModel($model, self::$creating);
        });

        static::updating(function ($model) use ($self) {
            $self->setUserEventOnModel($model, self::$updating);
        });

        static::saving(function ($model) use ($self) {
            if (!empty($model->id)) {
                $self->setUserEventOnModel($model, self::$saving);
            }
        });

        static::deleting(function ($model) use ($self) {
            $self->setUserEventOnModel($model, self::$deleting);
        });
    }

    /**
     * Set userEvent on the current model depending upon the
     * Event
     * @param $model
     * @param string $eventName
     */
    public function setUserEventOnModel(&$model, string $eventName = ''): void
    {
        foreach ($this->userActions as $fieldName => $dependsOn) {
            if (in_array($eventName, $dependsOn['depends_on_event'])) {
                $model->{$fieldName} = auth()->id();
            }
        }
    }
}
