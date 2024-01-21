<style>
.verify-form, .verify-ticket {
    background: #80808014;
    border-radius: 10px;
    margin: 0 auto;
    max-width: 500px;
    padding: 30px;
}

/* Style for the input element */
.verify-form input[type="text"],
.verify-ticket input[type="text"] {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    outline: none;
}

/* Style for the submit button */
.verify-form input[type="submit"],
.verify-ticket input[type="submit"] {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
    margin-top: 20px;
}

/* Hover effect for the submit button */
.verify-form input[type="submit"]:hover,
.verify-ticket input[type="submit"]:hover {
    background-color: #0069d9;
}


.verify-form .alert,
.verify-ticket .alert {
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
}

.verify-form .alert-danger,
.verify-ticket .alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.verify-form .alert-success,
.verify-ticket .alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

</style>
    <?php 
    if (isset($_COOKIE['verified_promoter'])) : ?>
     <form action="" method="POST" class="verify-ticket">
        <div class="alert"></div>
        <input type="text" name="ticket_number" id="ticket_number" placeholder="Enter ticket#" class="input-text">   
        <input type="submit" value="Submit" class="submit-button">
        </form>   
    <?php  else: ?>
        <form action="" method="POST" class="verify-form">
            <div class="alert"></div>
            <input type="text" name="username" id="email" placeholder="Enter your e-mail" class="input-text">
            <input type="text" name="password" id="password" placeholder="Enter your password" class="input-text">
            <input type="submit" value="Submit" class="submit-button">
        </form>
    <?php endif; ?>
<script>
(function($) {
    $(document).ready(function() {
        $('.verify-ticket').on('submit', function(e) {
            e.preventDefault();
            
            var ticketNumber = $('#ticket_number').val();
            
            // Validate ticket number
            if (ticketNumber.trim() === '') {
                $('.alert').addClass('alert-danger').removeClass('alert-success').html('Ticket number is required.');
                return false;
            }
            
            // Submit form using WP Ajax
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'verify_ticket',
                    ticket_number: ticketNumber,
                    verify_ticket_nonce: '<?php echo wp_create_nonce( 'verify_ticket' ); ?>'
                },
                success: function(response) {
                    // Handle the response from the server
                    if (response.status == 'success') {
                        $('.alert').addClass('alert-success').removeClass('alert-danger').html(response.msg);
                    } else {
                        $('.alert').addClass('alert-danger').removeClass('alert-success').html(response.msg);
                    }
                },
                error: function() {
                    // Handle any errors that occur during the AJAX request
                    alert('An error occurred during ticket verification');
                }
            });
        });
        $('.verify-form').on('submit', function(e) {
            e.preventDefault();
            
            var email = $('#email').val();
            var password = $('#password').val();
            
            // Validate email and password
            if (email.trim() === '' || password.trim() === '') {
                $('.alert').addClass('alert-danger').removeClass('alert-success').html('Email and password is required.');
                return false;
            }
            
            if (!validateEmail(email)) {
                $('.alert').addClass('alert-danger').removeClass('alert-success').html('Invalid email address.');
                return false;
            }
            
            // Submit form using WP Ajax
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'verify_ticket_vendor',
                    email: email,
                    password: password,
                    verify_ticket_nonce: '<?php echo wp_create_nonce( 'verify_ticket' ); ?>'
                },
                success: function(response) {
                    if(response.status == 'error') {
                        $('.alert').addClass('alert-danger').removeClass('alert-success').html(response.msg);
                    } else {
                        // Refresh the page
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(error);
                }
            });
        });
        
        function validateEmail(email) {
            // Email validation using regex
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
    });
})(jQuery);
</script>
