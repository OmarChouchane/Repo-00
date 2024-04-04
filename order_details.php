<?php


session_start(); 




/*
    not paid
    shipped
    delivered
*/ 


include 'server/connection.php';

if(isset($_POST['order_details_btn']) && isset($_POST['order_id'])){

    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");

    $stmt->bind_param('i',$order_id);

    $stmt->execute();

    $order_details = $stmt->get_result();

    $total_order_price = calculateTotalOrderPrice($order_details);


}else{
    header('location: account.php');
    exit();
}


function calculateTotalOrderPrice($order_details){

    $total = 0;

    foreach($order_details as $row){

        $price = $row['product_price'];
        $quantity = $row['product_quantity'];

        $total += ($price * $quantity);

    }

    return $total;

}



?>



<?php include('layouts/header.php'); ?>





    <!--Orders Details-->
    <section id="orders" class="orders order-detail container my-5 py-5" class="">
        <div class="container text-center mt-5 order-detail">
            <h2 class="font-weight-bold">Order Details</h2>
            <hr class="mx-auto">
        </div>

        <table class="mt-5 pt-5 mx-auto">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>


            <?php foreach($order_details as $row){?>

            <tr>
                <td>
                    <div class="product-info">
                        <img src="/assets/imgs/<?php echo $row['product_image'];?>" alt="">
                        <div>
                            <p class="mt-4"><?php echo $row['product_name'];?></p>
                        </div>
                    </div>
                </td>

                <td>$<span><?php echo $row['product_price'];?></span></td>
                <td><span class="product-quantity"><?php echo $row['product_quantity'];?></span></td>

            </tr>

            <?php }?>

        </table>


        <?php if($order_status == "not paid"){?>
            <form style="float: right;" action="payement.php" method="POST">
                <input type="hidden" name="total_order_price" value="<?php echo $total_order_price;?>">
                <input type="hidden" name="order_status" value="<?php echo $order_status;?>">
                <input class="btn order-details-btn" type="submit" name="order_pay_btn" value="Pay Now">
            </form>
        <?php } ?>

    </section>





<?php include('layouts/footer.php'); ?>