<?php

namespace Botble\Servicer\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Servicer\Http\Requests\TourRequest;

class TourForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(TOUR_MODULE_SCREEN_NAME)
            ->setValidatorClass(TourRequest::class)
            ->withCustomFields()
            ->hasTabs()
            ->add('name', 'text', [
                'label' => trans('core.base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core.base::forms.name_placeholder'),
                    'data-counter' => 200,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => trans('core.base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core.base::forms.description_placeholder'),
                    'data-counter' => 500,
                ],
            ])
            ->add('content', 'editor', [
                'label' => trans('core.base::forms.content'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core.base::forms.content'),
                ],
            ])
            ->add('address', 'text', [
                'label' => trans('servicer::forms.address'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('servicer::forms.address'),
                    'data-counter' => 300,
                ],
            ])
            ->add('status', 'select', [
                'label' => trans('core.base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => [
                    1 => trans('core.base::system.activated'),
                    0 => trans('core.base::system.deactivated'),
                ],
            ])
            ->add('price', 'number', [
                'label' => "Price",
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => "12000000",
                    'id' => 'number_price'
                ]
            ])
            ->add('price_children', 'number', [
                'label' => "Price of Children",
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => "12000000",
                    'id' => 'number_price'
                ]
            ])
            
            ->add('image', 'mediaImage', [
                'label' => trans('core.base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('order', 'number', [
                'label' => trans('core.base::forms.order'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('core.base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->setBreakFieldPoint('status');
    }
}