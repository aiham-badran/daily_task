<template>
  <main>
    <section>
      <div class="container">
        <h1 class="mt-5">About</h1>
        <nav class="navbar-light">
          <router-link to="/" class>Home</router-link>
          <router-link to="/about">About</router-link>
          <side-bar></side-bar>
        </nav>
      </div>
    </section>
  </main>
</template>

<script>
export default {
  name: "about"
};
</script>
