<template>
    <form id="coordsInput" @submit.prevent="buscarParadas">
        <div class="input-group">
            <input type="text" class="shadow-lg form-control" v-model="origen" placeholder="Ingresa tu origen" name="origen">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" @click="usarUbicacion" title="Usa tu ubicación actual">
                    <crosshair-icon size="1x"/>
                </button>
            </div>
        </div>
        <div class="lookup-dotted-line my-3"></div>
        <div class="input-group">
            <input type="text" class="shadow-lg form-control" v-model="destino" placeholder="Busca tu destino" name="destino">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <search-icon size="1x"></search-icon>
                </button>
            </div>
        </div>
    </form>
</template>
<script>
import { CrosshairIcon, SearchIcon } from 'vue-feather-icons'

export default {
    data() {
        return {
            origen: '',
            destino: '',
        }
    },
    components: { CrosshairIcon, SearchIcon },
    methods: {
        usarUbicacion() {
            let instance = this
            navigator.geolocation.getCurrentPosition((pos) => {
                instance.origen = pos.coords.latitude + " " + pos.coords.longitude
            })
        },
        buscarParadas() {
            let instance = this

            this.$emit('cargandoParadas')

            this.$http.get('paradas-cercanas', {
                params: {
                    origen: instance.origen,
                    destino: instance.destino
                }
            }).catch((r) => {
                alert(r.response.data.message)
                instance.$emit('paradasActualizadas', [])
            }).then((r) => {
                instance.$emit('paradasActualizadas', r.data.data || [])
            })
        }
    },
}
</script>

<style lang="css" scoped>
.minimized .lookup-dotted-line {
    height: 2em
}

.lookup-dotted-line {
    height: 3.2em;
    border: dashed 1px currentColor;
    width: 0;
    margin-left: 50%;
    margin-right: 50%;
}
</style>
