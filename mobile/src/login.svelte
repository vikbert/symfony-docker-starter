<script>
  import { login } from './client';
  import { isAuthenticated } from './store';

  let loginData = {
    username: '',
    password: '',
  };

  let isAuth = false;
  let unsubscribe = isAuthenticated.subscribe((value) => {
    isAuth = value;
  });

  const handleSubmit = (event) => {
    event.preventDefault();
    login(loginData);
  };

  const handleSsoLogin = () => {};

  $: console.log({
    form: loginData,
    isAuth,
  });
</script>

<div class="card px-2 py-2">
  <div class="card-title text-centered">
    <h3>Login</h3>
  </div>

  <div class="card-content">
    <form>
      <label class="my-2">
        User
        <input type="text" name="username" bind:value={loginData.username} />
      </label>

      <label class="my-2">
        Password
        <input type="password" name="password" bind:value={loginData.password} />
      </label>

      <div class="text-centered">
        <button
          class="is-primary is-rounded my-2"
          type="submit"
          on:click={handleSubmit}>
          Login
        </button>
        <button
          class="is-primary is-rounded is-cleared my-2"
          on:click={handleSsoLogin}>
          SSO
        </button>
      </div>
    </form>
  </div>
</div>
