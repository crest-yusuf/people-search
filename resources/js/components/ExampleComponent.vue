
<template>
    <div class="container">
        <SearchComponent />
    </div>
</template>
<script>
import SearchComponent from './Index/Search.vue'
export default {
    components: {
        SearchComponent: SearchComponent
    }
}
</script>
<style scoped>
.container {
    margin-bottom: 20px
}
</style>