<template>
  <div id="home" class="mt-6">
    <enter-account-number />
  </div>
</template>

<script>
  import EnterAccountNumber from '../components/EnterAccountNumber'

  export default {
    name: 'Home',

    components: {
      EnterAccountNumber,
    },
  }
</script>
