<template>
    <tr>

        <th scope="row">{{ index + 1 }}</th>
        <td>{{ ele.data.attributes.name }}</td>
        <td>
            <router-link class="btn btn-warning"  :to="{ name: 'vote page', params: { ele_id: ele.data.ele_id } }" :class="{ disabled: isDisable || this.ele.data.attributes.voted }">
            {{ ele.data.attributes.voted ? 'Voted' : 'Vote' }}
            </router-link>
        </td>
        
    </tr>
</template>


<script>
export default {
  props: [
            'index',
            'ele',
            'isDisable'
        ],
    mounted(){

    }
}
</script>



<style scoped>

</style>