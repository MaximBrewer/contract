<template>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header" v-if="auction.contragent">
          #{{ auction.id }} {{ auction.contragent.title }}
        </div>
        <ul class="list-group list-group-flush" v-if="auction.store">
          <li class="list-group-item" v-if="auction.store.federal_district">
            {{ auction.store.federal_district.title }}
          </li>
          <li class="list-group-item" v-if="auction.store.region">
            {{ auction.store.region.title }}
          </li>
          <li class="list-group-item" v-if="auction.store.address">
            {{ auction.store.address }}
          </li>
          <li class="list-group-item">{{ __(auction.mode) }}</li>
          <li class="list-group-item" style="display: flex; flex-wrap: wrap">
            <div
              v-for="(n, index) in imageList"
              :data-index="index"
              :key="index"
              style="padding: 0.5em 0.7em"
            >
              <img
                :src="n.url"
                alt
                style="max-width: 10em; cursor: pointer"
                @click="open($event)"
              />
            </div>
          </li>
        </ul>
      </div>
      <br />
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header" v-if="auction.product">
          {{ auction.product.title }}
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-if="auction.contragent">
            {{ auction.contragent.fio }}
          </li>
          <li class="list-group-item" v-if="auction.contragent">
            {{ auction.contragent.phone }}
          </li>
          <li class="list-group-item" v-if="auction.multiplicity">
            {{ __("Auction multiplicity") }}: {{ auction.multiplicity.title }}
          </li>
          <li class="list-group-item">
            {{ __("Auction step") }}: {{ auction.step }}₽
          </li>
          <li v-if="auction.tags.length" class="list-group-item">
            {{ __("Auction tags") }}:
            <ul>
              <li v-for="(tag, index) in auction.tags" :key="index">
                {{ tag.title }}
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <br />
    </div>
    <div class="col-md-12 p-wpadding" v-if="auction.mode === 'price2day'">
      <div class="card text-center">
        <div class="card-header">{{ __("Условия торгов") }}</div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            С начала и до конца торгов в любое время
          </li>
          <li class="list-group-item d-flex">
            <div class="w-50 border-right">
              <h5>Покупатель имеет право:</h5>
              <ul class="text-left">
                <li>забронировать или отменить бронь</li>
                <li>дать или забрать гарантию покупки</li>
                <li>выставить счет</li>
                <li>оплатить и не оплачивать счет по согласию с продавцом</li>
              </ul>
            </div>
            <div class="w-50">
              <h5>Продавец имеет право:</h5>
              <ul class="text-left">
                <li>отменить бронь покупателя</li>
                <li>
                  не отгружать продукцию по счету по согласию с покупателем
                </li>
                <li>
                  если оплата от покупателя принята, продавец должен отгрузить
                  продукцию соответственно описанию данных торгов, в количестве
                  и цене установленной в данных торгах
                </li>
              </ul>
            </div>
          </li>
          <li class="list-group-item text-left">
            <p>
              После торгов любые изменения торгов запрещены торговой площадкой и
              доступны в соответствии с разрешениями для просмотра каждого из
              участников торгов, как и во время торгов, в архиве.
            </p>
          </li>

          <li class="list-group-item text-left">
            <p>
              Покупатель имеет право получить, а продавец обязан предоставить
              максимально полные и точные ответы в чате данных торгов по своей
              продукции и условиям работы.
            </p>

            <p>
              В случае несоблюдения любой из сторон правил торгов, другая
              сторона имеет право изменить отзыв о не соблюдающей правила торгов
              компании на неотличный и, по желанию, перейти к диспуту.
            </p>
          </li>

          <li class="list-group-item text-left">
            <p>
              ВНИМАНИЕ! Торговая площадка определяет условия заключения сделки,
              но сделка осуществляется напрямую между компаниями-участниками
              торгов.
            </p>

            <p>
              На данный момент торговая площадка еще не проверяет каждого
              зарегистрированного сотрудника на актуальность отношения к
              компании-создателю аукциона. Но дает возможность
              компании-создателю аукциона осуществлять такую проверку.
            </p>

            <p>
              Торговая площадка не проводит проверку контрагентов на предмет
              правильности реквизитов.
            </p>
            <p>
              В добавление к выставленному счету с торговой площадки дождитесь
              уточненного счета от компании-создателя торгов, обязательно
              сверьте ИНН.
            </p>

            <p>Читайте отзывы о Создателе Данного Торга.</p>
            <p>
              Читайте отзывы о тех, кто оставил отзывы о создателе данного
              Торга.
            </p>
            <p>
              Именно в момент оплаты будьте предельно внимательны! Остерегайтесь
              мошенников!
            </p>
            <p>
              Обо всех подозрительных ситуациях сообщайте - сверху отправить
              сообщение - cross-contract.ru
            </p>
          </li>
        </ul>
      </div>
      <br />
    </div>
    <div class="col-md-12 p-wpadding" v-if="auction.mode === 'callApp'">
      <div class="card text-center">
        <div class="card-header">{{ __("Условия торгов") }}</div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            С начала и до конца торгов в любое время
          </li>
          <li class="list-group-item text-left">
            <p>
              Покупатель имеет право получить, а продавец обязан предоставить
              максимально полные и точные ответы в чате данных торгов по своей
              продукции и условиям работы.
            </p>

            <p>
              Данные торги созданы только в информационных целях, чтобы
              предприятие могло показать свою продукцию и возможные производящие
              мощности.
            </p>

            <p>
              Цель данных торгов - чтобы покупатель мог найти себе пул
              потенциальных поставщиков, а поставщики составили себе списки
              потенциальных покупателей.
            </p>

            <p>
              ВАЖНО: Чтобы принять участие в распределении продукции данного
              предприятия, Вам нужно добавиться в данные торги
            </p>
          </li>

          <li class="list-group-item text-left">
            <p>
              ВНИМАНИЕ! Торговая площадка определяет условия заключения сделки,
              но сделка осуществляется напрямую между компаниями-участниками
              торгов.
            </p>

            <p>
              На данный момент торговая площадка еще не проверяет каждого
              зарегистрированного сотрудника на актуальность отношения к
              компании-создателю аукциона. Но дает возможность
              компании-создателю аукциона осуществлять такую проверку.
            </p>

            <p>
              Торговая площадка не проводит проверку контрагентов на предмет
              правильности реквизитов.
            </p>
            <p>
              В добавление к выставленному счету с торговой площадки дождитесь
              уточненного счета от компании-создателя торгов, обязательно
              сверьте ИНН.
            </p>

            <p>Читайте отзывы о Создателе Данного Торга.</p>
            <p>
              Читайте отзывы о тех, кто оставил отзывы о создателе данного
              Торга.
            </p>
            <p>
              Именно в момент оплаты будьте предельно внимательны! Остерегайтесь
              мошенников!
            </p>
            <p>
              Обо всех подозрительных ситуациях сообщайте - сверху отправить
              сообщение - cross-contract.ru
            </p>
          </li>
        </ul>
      </div>
      <br />
    </div>
    <div class="col-md-12 p-wpadding" v-if="auction.mode === 'future'">
      <div class="card text-center">
        <div class="card-header">{{ __("Условия торгов") }}</div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex">
            <div class="w-50 border-right pr-4">
              <h5 class="text-left">
                со старта торгов до старта времени ограничения покупателя,
                покупатель имеет право:
              </h5>
              <ul class="text-left">
                <li>забронировать или отменить бронь</li>
                <li>дать или забрать гарантию оплаты</li>
              </ul>
            </div>
            <div class="w-50 pl-4">
              <h5 class="text-left">
                со старта торгов до времени ограничения продавца, продавец имеет
                право:
              </h5>
              <ul class="text-left">
                <li>отменить брони покупателя</li>
                <li>изменить объем торгов</li>
                <li>изменить цену начала торгов</li>
              </ul>
            </div>
          </li>
          <li class="list-group-item d-flex">
            <div class="w-50 border-right pr-4">
              <h5 class="text-left">
                После старта времени ограничения покупателя:
              </h5>
              <ul class="text-left">
                <li>
                  при бронировании продукции покупатель дает гарантию на оплату
                  счета
                </li>
                <li>
                  покупатель не может удалить бронь, забрать гарантию, не
                  оплачивать счет.
                </li>
                <li>
                  Продавец не имеет права менять время ограничения покупателя и
                  время ограничения продавца, время начала и окончания торгов.
                </li>
              </ul>
            </div>
            <div class="w-50 pl-4">
              <h5 class="text-left">
                После старта времени ограничения продавца:
              </h5>
              <ul class="text-left">
                <li>
                  продавец не может удалять ставки изменять объем торгов,
                  изменять начальную цену торгов, изменять описание продукции.
                </li>
                <li>
                  продавец дает гарантию отгрузки продукции, соответствующей
                  описанию в торгах, цене и объему выигрышной ставки покупателя
                  данному покупателю.
                </li>
              </ul>
            </div>
          </li>
          <li class="list-group-item text-left">
            <p>
              После торгов любые изменения торгов запрещены торговой площадкой и
              доступны в соответствии с разрешениями для просмотра каждого из
              участников торгов, как и во время торгов, в архиве.
            </p>
          </li>

          <li class="list-group-item text-left">
            <p>
              Покупатель имеет право получить, а продавец обязан предоставить
              максимально полные и точные ответы в чате данных торгов по своей
              продукции и условиям работы.
            </p>
            <p>
              В случае несоблюдения любой из сторон правил торгов, другая
              сторона имеет право изменить отзыв о не соблюдающей правила торгов
              компании на неотличный и , по желанию, перейти к диспуту.
            </p>
          </li>

          <li class="list-group-item text-left">
            <p>
              ВНИМАНИЕ! Торговая площадка определяет условия заключения сделки,
              но сделка осуществляется напрямую между компаниями-участниками
              торгов.
            </p>

            <p>
              На данный момент торговая площадка еще не проверяет каждого
              зарегистрированного сотрудника на актуальность отношения к
              компании-создателю аукциона. Но дает возможность
              компании-создателю аукциона осуществлять такую проверку.
            </p>

            <p>
              Торговая площадка не проводит проверку контрагентов на предмет
              правильности реквизитов.
            </p>
            <p>
              В добавление к выставленному счету с торговой площадки дождитесь
              уточненного счета от компании-создателя торгов, обязательно
              сверьте ИНН.
            </p>

            <p>Читайте отзывы о Создателе Данного Торга.</p>
            <p>
              Читайте отзывы о тех, кто оставил отзывы о создателе данного
              Торга.
            </p>
            <p>
              Именно в момент оплаты будьте предельно внимательны! Остерегайтесь
              мошенников!
            </p>
            <p>
              Обо всех подозрительных ситуациях сообщайте - сверху отправить
              сообщение - cross-contract.ru
            </p>
          </li>
          <li class="list-group-item d-flex">
            <div class="w-50 border-right pr-4">
              <p class="text-left">
                время до старта ограничения покупателя:
                {{
                  new Date(
                    auction.finish_at.getTime() - $root.time.getTime() - auction.delay_buy * 60000
                  ) | formatDateTime
                }}
                мин
              </p>
            </div>
            <div class="w-50 pl-4">
              <p class="text-left">
                время до старта ограничений продавца:
                {{ new Date(
                    auction.finish_at.getTime() - $root.time.getTime() - auction.delay_sell * 60000
                  ) | formatDateTime }} мин
              </p>
            </div>
          </li>
        </ul>
      </div>
      <br />
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-header">{{ __("Auction start") }}</div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-if="!!auction.start_at">
            {{ auction.start_at | formatDateTime }}
          </li>
        </ul>
      </div>
      <br />
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-header">{{ __("During time") }}</div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-if="$root.time">
            {{ $root.time | formatDateTime }}
          </li>
        </ul>
      </div>
      <br />
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-header">{{ __("Auction finish") }}</div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-if="!!auction.finish_at">
            {{ auction.finish_at | formatDateTime }}
          </li>
        </ul>
      </div>
      <br />
    </div>
  </div>
</template>
<script>
import VuePureLightbox from "vue-pure-lightbox";
import fancyBox from "vue-fancybox";
export default {
  components: {
    VuePureLightbox,
  },
  props: {
    auction: {
      type: Object,
      default: {},
    },
  },
  data() {
    return {
      imageList: [],
    };
  },
  mounted() {
    window.addEventListener("update-time", this.updateTime);
    let list = [];
    for (let img of this.auction.images) {
      this.imageList.push({
        url: img.path,
      });
    }
  },
  destroyed() {
    window.removeEventListener("update-time", this.updateTime);
  },
  methods: {
    updateTime(e) {
      console.log(e);
    },
    open(e) {
      fancyBox(e.target, this.imageList);
    },
  },
};
</script>