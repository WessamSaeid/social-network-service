<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
   
    'email' => 'يجب أن يكون عنوان البريد إلكتروني صالح',
    'image' => 'يجب ادخال صورة صالحة',
    'in' => ':attribute المحدد غير صحيح',
    'max' => [
        'string' => 'يجب الا يزيد :attribute عن :max حرف',
    ],
    'password' => 'كلمة المرور غير صحيحة',
    'required' => 'يجب ادخال :attribute',
    'string' => 'يجب ان يتكون :attribute من حروف',
    'unique' => 'هذا :attribute مستخدم من قبل',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        "name" => 'الاسم',
        "email" => 'البريد الكتروني',
        "password" => 'كلمة المرور',
        "image" => 'الصورة الشخصية',
        "user_id" => 'رقم المستخدم',
        "text" => 'النص',
    ],

];
