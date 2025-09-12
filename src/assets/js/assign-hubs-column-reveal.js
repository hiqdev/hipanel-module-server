// implementation of "show next pair" behavior for Switches/APCs columns
((($) => {
  "use strict";

  const TITLES = ["Switches", "APCs"];
  const BTN_HTML = "<i class='fa fa-fw fa-plus'></i>";

  // True if any input/select inside li is filled
  const isFilled = ($li) => {
    let ok = false;
    $li.find("input,select").each(function () {
      const $f = $(this);
      const valNotEmpty = ($f.val() ?? "").toString().trim() !== "";
      if (valNotEmpty) {
        ok = true;
        return false;
      }
    });
    return ok;
  };

  // Initial state: show all up to last filled, and keep one empty visible
  const initialLayout = ($col) => {
    const $lis = $col.find("ol:first>li");
    if (!$lis.length) return;

    const items = $lis.toArray();
    const lastFilled = items.findLastIndex((el) => isFilled($(el)));

    // Keep at least one pair visible:
    // - If nothing is filled, show the first item
    // - Otherwise, show up to the last filled plus one more (if exists)
    let lastToShow;
    if (lastFilled === -1) {
      lastToShow = 0;
    // } else if (lastFilled < items.length - 1) {
    //   lastToShow = lastFilled;
    } else {
      lastToShow = lastFilled;
    }

    items.forEach((el, i) => {
      let isVisible = i <= lastToShow;
      $(el).toggle(isVisible);
      $(el).find('input').prop("disabled", !isVisible)
    });
  };

  // Create one button that just shows the next hidden row on each click
  const ensureButton = ($col) => {
    const hasHidden = $col.find("ol:first>li:hidden").length > 0;
    if (!hasHidden) {
      $col.data("wrap")?.remove();
      $col.removeData("wrap").removeData("btn");
      return;
    }

    let $wrap = $col.data("wrap");
    let $btn = $col.data("btn");

    if (!$wrap || !$wrap.parent().length) {
      $wrap = $("<div/>").css({
        marginBottom: "10px",
      });
      $btn = $("<button/>", {
        type: "button",
        class: "btn btn-success btn-sm assign-hubs-reveal",
        style: "min-width: 10%;",
        html: BTN_HTML,
      }).css({
        marginLeft: "40px",
      });

      $btn.on("click", () => {
        const $next = $col.find("ol:first>li:hidden").first();
        if ($next.length) {
          $next.show().find('input').prop("disabled", false);
        }
        if ($col.find("ol:first>li:hidden").length === 0) {
          $wrap.remove();
          $col.removeData("wrap").removeData("btn");
        }
      });

      $wrap.append($btn);

      const $ol = $col.find("ol").first();
      if ($ol.length) $ol.after($wrap); else $col.prepend($wrap);

      $col.data({
        wrap: $wrap,
        btn: $btn,
      });
    } else {
      $btn.html(BTN_HTML).prop("disabled", false);
    }
  };

  const initCol = ($col) => {
    initialLayout($col);
    ensureButton($col);
  };

  const init = () => {
    $(".col-md-4").filter((_, el) => {
      const title = $(el).find("h5").first().text()?.trim() ?? "";
      return TITLES.includes(title);
    }).each((_, el) => {
      try {
        initCol($(el));
      } catch {
      }
    });
  };

  // Run once on DOM ready; no input/mutation recomputation
  $(() => init());

  // Optional API
  window.AssignHubsColumnReveal = {
    init: () => init(),
    initialLayout: (el) => initialLayout($(el)),
  };
})(jQuery));
