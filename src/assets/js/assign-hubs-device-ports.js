((($) => {
  "use strict";

  const requests = {};

  const loadPorts = (url, hubId) => {
    requests[hubId] ??= $.getJSON(url, { id: hubId }).then((ports) => ports, () => []);

    return requests[hubId];
  };

  const renderList = ($input, ports, emptyText) => {
    if (!ports.length) {
      return $("<div class='text-muted'>").text(emptyText);
    }
    const $list = $("<ul class='list-unstyled device-ports'>");
    ports.forEach(({ port, device }) => {
      const $pick = $("<a href='#' class='device-port'>").text(port).on("mousedown", (event) => {
        event.preventDefault();
        $input.val(port).trigger("input").trigger("change");
        $input.popover("destroy");
      });
      $list.append($("<li>").append($pick, $("<span class='text-muted'>").text(` ${device}`)));
    });

    return $list;
  };

  $(document).on("focus", "[data-ports-url] input[id$='_port']", function () {
    const $input = $(this);
    const $config = $input.closest("[data-ports-url]");
    const hubId = $(`#${this.id.replace(/_port$/, "_id")}`).val();
    if (!hubId) {
      return;
    }
    loadPorts($config.data("ports-url"), hubId).then((ports) => {
      if (document.activeElement !== this) {
        return;
      }
      $input.popover("destroy").popover({
        html: true,
        trigger: "manual",
        placement: "bottom",
        container: "body",
        title: $config.data("ports-title"),
        content: renderList($input, ports, $config.data("ports-empty")),
      }).popover("show");
    });
  });

  $(document).on("blur", "[data-ports-url] input[id$='_port']", function () {
    $(this).popover("destroy");
  });
}))(jQuery);
