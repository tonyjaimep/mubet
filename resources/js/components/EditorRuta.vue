<template>
    <form class="editor-ruta">
        <div class="row">
            <input type="text" class="mb-3 form-control" v-model="ruta.name" placeholder="Nombre">
            <input type="text" class="mb-3 form-control" v-model="ruta.alias" placeholder="Otros nombres">
            <div class="mb-3 input-group">
                <div class="input-group-prepend">
                    Tarifa: $
                </div>
                <input type="number" step="0.1" class="form-control" v-model="ruta.tarifa" placeholder="Tarifa">
            </div>
            <button class="btn btn-primary">Guardar</button>
        </div>
    </form>
</template>

<script>
const rutaDefecto = {
    nombre: '',
    alias: '',
    tarifa: null
};

export default {
    data() {
        return {
            ruta: _.clone(rutaDefecto)
        }
    },
    props: ['modelo-ruta'],
    watch: {
        modeloRuta() {
        }
    }
}
</script>

<style lang="css" scoped>
</style>
