<script>
  import Login from './login.svelte';
</script>

<header>
  <nav class="topbar">
    <div class="container space-between">
      <span
        width="30"
        class="iconify"
        data-icon="ant-design:experiment-outlined"
        data-inline="false">
      </span>
      <span
        width="30"
        class="iconify"
        data-icon="carbon:user-avatar-filled-alt"
        data-inline="false">
      </span>
    </div>
  </nav>
</header>

<main class="main">
  <div class="hero is-primary">
    <div class="container text-centered">
      <h1>SSO POC</h1>
    </div>
  </div>
  <div class="container centered-xy">
    <Login/>
  </div>
</main>

<footer class="footer">
  <div class="container text-centered">
    <a href="https://vikbert.github.io/pixss/demo">build with pixss</a>
  </div>
</footer>
<style>
  a {
    color: white;
  }
</style>
