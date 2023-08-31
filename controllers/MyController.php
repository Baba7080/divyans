<?php
require_once 'controllers/BaseController.php';
require_once 'models/MyModel.php';
require 'vendor/razorpay/Razorpay.php';
use Razorpay\Api\Api;
class MyController extends BaseController {

    public function __construct()
    {
        $this->userModel = $this->model('MyModel');
        $this->pujaType = $this->model('PujaModel');
    }

    public function viewForm()
    {
        if(isset($_GET['id']))
        {
            $data['id'] = $_GET['id'];
            $this->loadView('addPuja' , $data);
        }
    }

    public function savePuja()
    {
       if(isset($_POST))
       {

            $name = $_POST['userName'];
            $mob = $_POST['phoneNo'];
            $date = $_POST['selectedDate'];
            $email = $_POST['mail'];
            $id = $_POST['puja_id'];
            $poojaData = $this->getpooja($id);
            $price = $poojaData[0]['price'];
            if($price){
                $payment = $this->payment($price);
            }
            // print_r($payment);die;
            $sql = "INSERT INTO puja (name, phone , date , email , puja_id) VALUES ('$name', '$mob', '$date', '$email' , '$id')";
            $res = $this->userModel->executeQuery($sql);
            $this->index();
       }
    }
    public function getpooja($id){
        if($id){
            $poojaDetail =  "SELECT * FROM pujatype WHERE id = '$id'";
            $res = $this->userModel->executeQuery($poojaDetail);

            return $res;
        }
    }
    public function payment($amount){
        if(isset($amount) && !empty($amount)){
            
            $keyId = 'rzp_test_6bHn7PkDnJMYJx';
            $keySecret = 'cNQkToeIj4BTKvwFtXlpRMWJ';
            $api = new Api($keyId, $keySecret);
            $orderData = [
                'receipt'         => 3456,
                'amount'          => $amount * 100,
                'currency'        => 'INR',
                'payment_capture' => 1
            ];
            $razorpayOrder = $api->order->create($orderData);
            $razorpayOrderId = $razorpayOrder['id'];
            $_SESSION['razorpay_order_id'] = $razorpayOrderId;
            $displayAmount = $amount = $orderData['amount'];
            // print_r(["razor"=>$razorpayOrder,"amaunt"=>$displayAmount]);die;
            // $api = new Api($keyId, $keySecret);
            // $api->payment->fetch($paymentId);

            // $orderAmount = 1000*100; // Replace with the actual order amount
            // $orderId = "ORD123456"; 
            // // Construct the Razorpay payment URL
            $redirectUrl = "https://api.razorpay.com/v1/checkout/embedded";
            $params = array(
                'key' => $keyId,
                'amount' => $amount * 100,
                'name' => 'Your Company Name',
                'description' => 'Payment for Order #' . $razorpayOrderId,
                // 'order_id' => $orderId,
                'prefill' => array(
                    'name' => 'John Doe',
                    'email' => 'johndoe@example.com',
                    'contact' => '1234567890'
                ),
                'notes' => array(
                    'address' => '123, Street Name, City'
                ),
                'theme' => array(
                    'color' => '#F37254'
                )
            );
            
            $paymentUrl = $redirectUrl . '?' . http_build_query($params);
            $responce = header("Location: " . $paymentUrl);
            return $responce;
        }

    }
}
?>
