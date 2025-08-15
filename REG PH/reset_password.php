<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PASSWORD RESET</title>
    
<style>

    *{
        padding: 0;
    }
    body{
        padding: auto;
        background-color: black;
        display:flex;
        justify-content: center;
        align-content: center;
        align-items: center;
    }
    .container{
        padding: 30px;
        background-color: rgb(17, 85, 62);
        width: 190px;
        border-radius: 10px;
        }
        input{
            padding: 10px;
            border-radius: 10px;
            background-color: rgb(78, 221, 226);
            height: 15px;
        }
        
        button:hover{
            background-color: azure;
            padding: 10px;
            
        
        }
        button{
            padding: 10px;
            border-radius: 10px;
        }
        h2{
            color: azure;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }


</style>
</head>

<body>
 <form>
    <h2>Resete your password</h2>
        <div class="container"> 
        <input type="text"placeholder="ENTER NEW PASSWORD" required><br><br>
    
        <button type="submit">submit</button>
    </div>
</form>
</body>
</html>
