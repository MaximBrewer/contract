<template>
  <yandex-map
    @map-was-initialized="mapLoaded"
    zoom="8"
    :settings="settings"
    :controls="controls"
    :coords="logistic.coords && logistic.coords.split(',').length ? logistic.coords.split(',') : defaultCoords"
  >
    <ymap-marker
      v-if="logistic.coords && logistic.coords.split(',').length"
      :coords="logistic.coords.split(',')"
      :marker-id="logistic.id"
      :hint-content="logistic.address"
    />
  </yandex-map>
</template>
<script>
import { yandexMap, ymapMarker } from "vue-yandex-maps";
export default {
  components: { yandexMap, ymapMarker },
  props: ["logistic"],
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
          if (!logistic.coords.split(",").length)
            obj.geoObjects.add(result.geoObjects);
        });

      obj.events.add("click", function(e) {
        var coords = e.get("coords");
        var address = e.get("address");
        ymaps.geocode(coords).then(function(res) {
          app.logistic.address = res.geoObjects.get(0).getAddressLine();
          let address = app.logistic.address.split(",");
          if (address.length) {
            axios
              .post(
                "/web/v1/address",
                { address: address }
              )
              .then(function(resp) {
                // if (resp.data[0]) {
                  app.logistic.region = resp.data[0];
                  app.logistic.federal_district = resp.data[1];
                // }
              });
          }
        });
        obj.geoObjects.removeAll().add(new ymaps.Placemark(coords));
        app.logistic.coords = coords.join(",");
      });
    }
  }
};
</script>