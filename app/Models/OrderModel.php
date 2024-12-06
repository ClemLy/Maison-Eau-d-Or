<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    protected $allowedFields = [
        'id_user',
        'order_date',
        'phone_number_order',
        'address_street',
        'address_city',
        'address_zip',
        'address_country'
    ];

}