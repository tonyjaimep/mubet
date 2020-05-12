<template>
    <div id="home">
        <section id="lookup">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-md-8">
                        <h1 class="text-center my-5" v-if="!busquedaHecha">Encuentra tu ruta</h1>
                        <h2 class="text-center my-5" v-else-if="cargando">Buscando tu ruta</h2>
                        <h3 class="text-center my-5" v-else-if="paradas.length">¡Encontramos tu ruta!</h3>
                        <h3 class="text-center my-5" v-else>¡Debe haber otro medio!</h3>
                        <coords-input @cargandoParadas="mostrarBusqueda" @paradasActualizadas="actualizarParadas"/>
                    </div>
                </div>
            </div>
        </section>
        <section id="results" v-if="busquedaHecha">
            <div class="container">
                <div class="text-center" v-if="cargando">
                    <loader-icon class="my-5 text-center spinning" size="2x"/>
                </div>
                <div v-else class="row justify-content-center">
                    <div class="col-sm-10 col-lg-8">
                        <div class="alert alert-warning" v-if="!paradas.length">
                            <div class="row">
                                <div class="d-none d-sm-block col-sm-3 text-center pt-5">
                                    <alert-triangle-icon size="3x"/>
                                </div>
                                <div class="col-sm-9">
                                    <h5>No se encontraron paradas cercanas a su origen o a su destino.</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-light mb-4 shadow-lg" v-for="parada in paradas" :key="parada.id">
                            <div class="card-body">
                                <div class="row no-gutters">
                                    <div class="d-none d-sm-block col-sm-3 text-center pt-5">
                                        <compass-icon size="3x"/>
                                    </div>
                                    <div class="col-sm-9">
                                        <h5>{{ parada.ruta.nombre }}</h5>
                                        <h6 v-if="parada.ruta.alias">{{ parada.ruta.alias }}</h6>
                                        <h6>{{ parada.estacion.direccion }}</h6>
                                        <code>
                                            lat: {{ parada.estacion.lat }}<br>
                                            lng: {{ parada.estacion.lat }}
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import CoordsInput from '../components/CoordsInput'

import { AlertTriangleIcon, CompassIcon, LoaderIcon } from 'vue-feather-icons'

export default {
    data() {
        return {
            cargando: false,
            busquedaHecha: false,
            paradas: [],
        }
    },
    components: { AlertTriangleIcon, CompassIcon, CoordsInput, LoaderIcon },
    methods: {
        mostrarBusqueda() {
            this.busquedaHecha = true
            this.cargando = true
        },
        actualizarParadas(paradas) {
            this.cargando = false
            this.paradas = paradas
        }
    }
}
</script>

<style media="screen">
    .spinning {
        animation: spin 2s infinite forwards linear;
    }
    @keyframes spin {
        0% {
            transform: rotate(0);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
