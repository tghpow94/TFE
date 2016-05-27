
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>jQuery UI Dialog - Animation</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="/resources/demos/style.css">

    <script>
        $(document).ready(function() {
            $('option').mousedown(function(e) {
                e.preventDefault();
                $(this).prop('selected', !$(this).prop('selected'));
                return false;
            });
        });
    </script>
</head>
<body>


<form action="test.php" method="post">
    <select  name="users[]" multiple="multiple" size="10">
        <option value="1">user1</option>
        <option value="2">user2</option>
        <option value="3">user3</option>
        <option value="4">user4</option>
        <option value="5">user5</option>
    </select>
    <button type="submit"></button>
    <?php
    var_dump($_POST['users'][0]);
    ?>
</form>


</body>
</html>
