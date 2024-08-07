Vue.createApp({
  data() {
    const orderModel = JSON.parse(document.getElementById("order-form-model").value);
    const orderOptions = JSON.parse(document.getElementById("order-form-options").value);

    return {
      submitted: false,
      ticketId: "",
      ticketLink: "#",
      trafficUnit: "mbps",
      order: orderModel,
      orderOptions: orderOptions,
      isIPMIDisabled: orderModel.administration === "Unmanaged (Included)",
      innerTotal: 0,
      originalConfig: orderModel.config,
      init: {
        administration: orderModel.administration,
        os: orderModel.os,
      }
    };
  },
  computed: {
    linkToTicket() {
      return this.ticketLink ? this.ticketLink : "#";
    },
    total() {
      let total = parseFloat(this.order.price);
      const simple = ["ip", "administration"];
      const advanced = ["ram", "hdd", "ssd", "raid"];
      let finalAttributes = simple;
      if (this.order.upgrade) {
        finalAttributes = finalAttributes.concat(advanced);
      }
      if (this.trafficUnit === "mbps") {
        finalAttributes = finalAttributes.concat(["traffic_mbps"]);
      } else {
        finalAttributes = finalAttributes.concat(["traffic_tb"]);
      }
      finalAttributes.forEach((attribute) => {
        const option = this.orderOptions[attribute][this.order[attribute]];
        if (!isNaN(parseFloat(option.price))) {
          total += parseFloat(option.price);
        }
        if (!isNaN(parseFloat(option.discount))) {
          total -= parseFloat(option.discount ?? 0);
        }
      });
      this.innerTotal = this.asCurrency(total) + " per month";

      return this.asCurrency(total);
    },
    changeSummary() {
      return [this.order.ram, this.order.hdd, this.order.ssd, this.order.upgrade];
    },
  },
  watch: {
    "order.administration"(newValue) {
      const isDisabled = newValue === "Unmanaged (Included)";
      if (isDisabled) {
        this.order.ipmi = "Yes";
      }
      this.isIPMIDisabled = isDisabled;
    },
    "order.upgrade"(newValue) {
      if (newValue === false) {
        this.order.ip = "2 (Included)";
        this.order.administration = this.init.administration;
        this.order.os = this.init.os;
      }
    },
    changeSummary(attributes) {
      let needToGetSummary = false;
      attributes.forEach((value, attribute) => {
        if (typeof value === "string" && !value.includes("Included")) {
          needToGetSummary = true;
        }
      });
      if (needToGetSummary && this.order.upgrade === true) {
        const _this = this;
        const formData = new FormData();
        Object.keys(this.order).forEach(key => formData.append(key, this.order[key]));
        this.$nextTick(() => {
          this.send("generate-summary", formData, function (rsp) {
            _this.order.config = rsp.summary;
          });
        });
      } else {
        this.order.config = this.originalConfig;
      }
    },
  },
  methods: {
    showPrice(attribute) {
      const price = this.orderOptions[attribute][this.order[attribute]].price;

      return price > 0 ? this.asCurrency(price) + " / Month" : price;
    },
    showHint(attribute) {
      const hint = this.orderOptions[attribute][this.order[attribute]].hint;

      return hint ?? "";
    },
    asCurrency(amount) {
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: this.order.currency.toUpperCase(),
        maximumFractionDigits: 2,
      }).format(amount);
    },
    scrollToTop() {
      window.scroll({
        top: 0,
        left: 0,
        behavior: 'smooth'
      })
    },
    handleSubmit(event) {
      event.preventDefault();
      const _this = this;
      const form = this.$refs.orderForm;
      const formData = new FormData(form);
      this.send(window.location.href, formData, function (rsp) {
        if (rsp.hasOwnProperty('ticketId') && rsp.hasOwnProperty('ticketLink')) {
          _this.ticketId = rsp.ticketId;
          _this.ticketLink = rsp.ticketLink;
        }
        _this.submitted = true;
        _this.scrollToTop();
      });
    },
    send(url, formData, success) {
      const btn = $(this.$refs.orderForm).find("[type=submit]").button("loading");
      $.ajax({
        url: url,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (rsp) {
          if (rsp.error) {
            hipanel.notify.error(rsp.error);
          } else {
            success(rsp);
          }
          btn.button("reset");
        },
        error: function (xhr, status, error) {
          hipanel.notify.error("Your order was not sent successfully.\n" + error);
        },
      });
    },
  },
}).mount("#irs-app");
