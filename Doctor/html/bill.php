<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Bill Generation</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/doctor.css" type="text/css">
    <style>
        /* CSS styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center; 
            vertical-align: middle;
            border: 4px solid #ddd;
            padding: 7px;
        }
        #total{
            font-weight: bold;
            margin-top: 50px;
            align-items: center;
            color: white;
            text-align: center;
            font-size: larger;
        }
        .removeEntryBtn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            width: 90px;
            margin-bottom: 24px;
        }
        .removeEntryBtn:hover {
            background-color: #d32f2f;
        }
        .nav{
            margin-left: -15px;
        }
    </style>
</head>
<?php
session_start();

if(isset($_POST['submit'])){
    $appt_id = $_POST['selectedAppointment'];

    $_SESSION['appt_id'] = $appt_id;
}
?>
<body>
    <div class="nav" id="mynavbar">
        <a href="">Health Consultancy</a>
        <div class="nav-right" id="navbar-right">
            <a href="logout.php">Logout</a>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </div>
    <div class="sidenav" id="mySidenav">
        <br>
        <a href="createtime.php">Create Time Slot</a>
        <a href="slots.php">Cancel Time Slot</a>
        <a href="booked_appt.php">View Booked Appointments</a>
        <a class="active" href="cal.php">Generate Bill</a> <!-- New link for bill generation -->
        <a href="../../Reg_Login/php/logout.php">Log Out</a>
    </div>
    <section class="dashboard">
        <div class="container">
            <h1>Medical Bill Generation</h1>
            <form id="billForm" action="../php/generate_bill.php" method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th style="width: 10%;">Quantity</th>
                            <th>Total</th>
                            <th style="width: 12%;">Remove</th> <!-- Add column for Remove button -->
                        </tr>
                    </thead>
                    <tbody id="entries">
                        <!-- Entries will be dynamically added here -->
                    </tbody>
                </table>
                <div id="total">Grand Total: $0.00</div>
                <button type="button" id="addEntry">Add Entry</button>
                <button type="submit">Generate Bill</button>
            </form>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            var entryCount = 0; // Initialize entry count
            var total = 0; // Initialize grand total

            $("#addEntry").click(function() {
                entryCount++; // Increment entry count
                var entryId = 'entry_' + entryCount; // Generate entry ID
                $("#entries").append('<tr class="entry" id="' + entryId + '"><td><select class="serviceSelect" name="serviceSelect[]"><option value="">Select a service...</option></select></td><td><input style="width=10%;" type="number" class="quantity" name="quantity[]" value="1" min="1"></td><td class="entryTotal"></td><td><button type="button" class="removeEntryBtn">X</button></td></tr>');
                
                // Populate select options from the database
                $.ajax({
                    url: '../php/get_services.php', // Server-side script to retrieve services
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(index, service) {
                            $('#' + entryId + ' .serviceSelect').append('<option value="' + service.ServiceID + '">' + service.Name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching services:', status, error);
                    }
                });

                calculateEntryTotal(entryId); // Calculate total for the new entry
            });

            $(document).on('change', '.serviceSelect, .quantity', function() {
                var entryId = $(this).closest('.entry').attr('id');
                calculateEntryTotal(entryId); // Recalculate total when service or quantity changes
            });

            $(document).on('click', '.removeEntryBtn', function() {
                var entryId = $(this).closest('.entry').attr('id');
                $('#' + entryId).remove();
                total = recalculateGrandTotal();
                $('#total').text('Grand Total: $' + total.toFixed(2));
            });

            function calculateEntryTotal(entryId) {
                var serviceId = $('#' + entryId + ' .serviceSelect').val();
                var quantity = $('#' + entryId + ' .quantity').val();
                var entryTotal = 0;

                if (serviceId !== '' && !isNaN(quantity) && quantity > 0) {
                    $.ajax({
                        url: '../php/get_service_price.php', // Server-side script to retrieve service price
                        method: 'GET',
                        data: { serviceID: serviceId },
                        dataType: 'json',
                        success: function(data) {
                            var price = data.price;
                            entryTotal = price * quantity;
                            $('#' + entryId + ' .entryTotal').text('$' + entryTotal.toFixed(2));
                            total = recalculateGrandTotal();
                            $('#total').text('Grand Total: $' + total.toFixed(2));
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching service price:', status, error);
                        }
                    });
                } else {
                    $('#' + entryId + ' .entryTotal').text('');
                    total = recalculateGrandTotal();
                    $('#total').text('Grand Total: $' + total.toFixed(2));
                }
            }

            function recalculateGrandTotal() {
                var grandTotal = 0;
                $('.entryTotal').each(function() {
                    var entryTotal = parseFloat($(this).text().replace('$', ''));
                    if (!isNaN(entryTotal)) {
                        grandTotal += entryTotal;
                    }
                });
                return grandTotal;
            }

            // Handle form submission
            $('#billForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                
                // Serialize form data
                var formData = $(this).serializeArray();
                
                // Display form data for debugging
                console.log(formData);
                
                // Proceed with form submission
                $.post('../php/generate_bill.php', formData, function(response) {
                    console.log(response); // Log the response from the server
                 });
            });
        });
    </script>
</body>
</html>
