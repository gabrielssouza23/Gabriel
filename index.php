<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');

    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      color: #023047
    }

    .page {
      display: flex;
      flex-direction: column;
      align-items: center;
      align-content: center;
      justify-content: center;
      width: 100%;
      height: 100vh;
      background-color: #480ca8;
    }

    .formLogin {
      display: flex;
      flex-direction: column;
      background-color: #fff;
      border-radius: 7px;
      padding: 40px;
      box-shadow: 10px 10px 40px rgba(0, 0, 0, 0.4);
      gap: 5px
    }

    .areaLogin img {
      width: 420px;
    }

    .formLogin h1 {
      padding: 0;
      margin: 0;
      font-weight: 500;
      font-size: 2.3em;
    }

    .formLogin p {
      display: inline-block;
      font-size: 14px;
      color: #666;
      margin-bottom: 25px;
    }

    .formLogin input {
      padding: 15px;
      font-size: 14px;
      border: 1px solid #ccc;
      margin-bottom: 20px;
      margin-top: 5px;
      border-radius: 4px;
      transition: all linear 160ms;
      outline: none;
    }


    .formLogin input:focus {
      border: 1px solid #f72585;
    }

    .formLogin label {
      font-size: 14px;
      font-weight: 600;
    }

    .formLogin a {
      display: inline-block;
      margin-bottom: 20px;
      font-size: 13px;
      color: #555;
      transition: all linear 160ms;
    }

    .formLogin a:hover {
      color: #f72585;
    }

    .btn {
      background-color: #f72585;
      color: #fff;
      font-size: 14px;
      font-weight: 600;
      border: none !important;
      transition: all linear 160ms;
      cursor: pointer;
      margin: 0 !important;

    }

    .btn:hover {
      transform: scale(1.05);
      background-color: #ff0676;

    }
  </style>
</head>

<body>

  <div class="page">
    <form method="POST" action="" class="formLogin">
      <h1>Login</h1>
      <p>Digite os seus dados de acesso no campo abaixo.</p>
      <label for="email">E-mail</label>
      <input type="email" name="email" placeholder="Digite seu e-mail" autofocus="true" />
      <label for="password">Senha</label>
      <input type="password" name="password" placeholder="Digite seu e-mail" />
      <input type="submit" value="Entrar" class="btn" />
    </form>
  </div>

  <!-- <h2>Login</h2>
  <section>
    <form action="">
      <input type="text" name="email" />
      <input type="text" name="password" />
      <input type="submit" value="Entrar">
    </form>
  </section> -->


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