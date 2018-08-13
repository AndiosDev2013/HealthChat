<html>
    <body>
        <h4>Hello <?php echo $name; ?></h4>
        <p>
            You have entered a new password to access HealthChat.
        </p>
        <p>
            <strong>To activate the new password, click here:</strong>
            <?php echo 'member/recover_password/' . $token . '/' . $password; ?>
        </p>
        <p>
            Oops, it was not you? No problem - just ignore this e-mail. Your old password will remain valid.
        </p>
        <p>
            Regards,<br />
            Your HealthChat Support Team
        </p>
    </body>
</html>