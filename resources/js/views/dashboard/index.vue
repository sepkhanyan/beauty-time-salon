<template>
  <div>
    <component :is="currentState" />
  </div>
</template>

<script>
import editorDashboard from './editor';

export default {
  name: 'Dashboard',
  components: { editorDashboard },
  data() {
    return {
      currentState: 'editorDashboard',
    };
  },
};
</script>
