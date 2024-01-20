<?php

function getCommissionStatus($commission_status){
    if($commission_status == 1){
        $commission_status = "Pending";
        $class = "label label-primary";
    }
    elseif($commission_status == 2){
        $commission_status = "Success";
        $class = "label label-success";
    }
    elseif($commission_status == 3){
        $commission_status = "On Hold";
        $class = "label label-secondary";
    }
    elseif($commission_status == 4){
        $commission_status = "Cancelled";
        $class = "label label-warning";
    }
    elseif($commission_status == 5){
        $commission_status = "Failed";
        $class = "label label-danger";
    }

    return ['commission_status' => $commission_status, 'class' => $class];
}

function getOrderStatus($order_status){
    if($order_status == 1){
        $order_status = "New Order";
        $class = "label label-warning";
    }
    elseif($order_status == 2){
        $order_status = "Out for Delivery";
        $class = "label label-info";
    }
    elseif($order_status == 3){
        $order_status = "Delivered";
        $class = "label label-success";
    }
    elseif($order_status == 4){
        $order_status = "Return Request";
        $class = "label label-warning";
    }
    elseif($order_status == 5){
        $order_status = "Return In Transit";
        $class = "label label-secondary";
    }
    elseif($order_status == 6){
        $order_status = "Returned";
        $class = "label label-light";
    }
    elseif($order_status == 7){
        $order_status = "Cancelled";
        $class = "label label-danger";
    }
    elseif($order_status == 8){
        $order_status = "Cancelled";
        $class = "label label-danger";
    }

    return ['order_status' => $order_status, 'class' => $class];
}
