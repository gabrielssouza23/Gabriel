<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <h2>Login</h2>
  <section>
    <form action="">
      <input type="text" name="email" />
      <input type="text" name="password" />
      <input type="submit" value="Entrar">
    </form>
  </section>


  <script>
    const form = document.querySelector('form')

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const obj = {
        action: 'login',
        email: document.querySelector('input[name="email"]').value,
        password: document.querySelector('input[name="password"]').value
      }
      const request = await fetch("Api/login.php", {
        method: 'post',
        body: JSON.stringify(obj)
      });

      const json = await request.json();
      console.log("🚀 ~ form.addEventListener ~ json:", json.email);

      if (json.email === undefined) {
        console.log("erro ao logar");
      } else {
        window.location.href = "./home.php";
        console.log("passou");
      }


    })
  </script>
</body>

</html>