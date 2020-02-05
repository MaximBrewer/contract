<template>
  <yandex-map
    @map-was-initialized="mapLoaded"
    zoom="8"
    :settings="settings"
    :controls="controls"
    :coords="store.coords && store.coords.split(',').length ? store.coords.split(',') : defaultCoords"
  >
    <ymap-marker
      v-if="store.coords && store.coords.split(',').length"
      :coords="store.coords.split(',')"
      :marker-id="store.id"
      :hint-content="store.address"
    />
  </yandex-map>
</template>
<script>
import { yandexMap, ymapMarker } from "vue-yandex-maps";
export default {
  components: { yandexMap, ymapMarker },
  props: ["store"],
  data() {
    return {
      controls: ['searchControl'],
      settings: {
        apiKey: "a5c4997f-eb1b-4fee-bea6-fb5c83005b5a",
        lang: "ru_RU",
        coordorder: "latlong",
        version: "2.1"
      },
      defaultCoords: [55.75222, 37.61556]
    };
  },
  methods: {
    mapLoaded(obj) {
      var app = this,
        geolocation = ymaps.geolocation;
      geolocation
        .get({
          provider: "yandex",
          mapStateAutoApply: true
        })
        .then(function(result) {
          if (!store.coords.split(",").length)
            obj.geoObjects.add(result.geoObjects);
        });

      obj.events.add("click", function(e) {
        var coords = e.get("coords");
        var address = e.get("address");
        ymaps.geocode(coords).then(function(res) {
          app.store.address = res.geoObjects.get(0).getAddressLine();
          let address = app.store.address.split(",");
          if (address.length) {
            axios
              .post(
                "/api/v1/address?csrf_token=" +
                  window.csrf_token +
                  "&api_token=" +
                  window.api_token,
                { address: address }
              )
              .then(function(resp) {
                // if (resp.data[0]) {
                  app.store.region = resp.data[0];
                  app.store.federal_district = resp.data[1];
                // }
              });
          }
        });
        obj.geoObjects.removeAll().add(new ymaps.Placemark(coords));
        app.store.coords = coords.join(",");
      });
    }
  }
};
</script>