(function ($) {
  var jobActiveShow;

  function selectFirstVisibleJob() {
    var $option = $(
      ".field-contactform-jobTitle .nice-select li.option:visible"
    ).first();
    var $dropdown = $option.closest(".nice-select");

    $dropdown.find(".selected").removeClass("selected");
    $option.addClass("selected");

    var text = $option.data("display") || $option.text();
    $dropdown.find(".current").text(text);

    $dropdown.prev("select").val($option.data("value")).trigger("change");
  }

  function setJobDetails(jobRow) {
    if (jobRow) {
      //$(jobActiveShow).find('.job-text.last-update').text($(jobRow).data('lastupdate') || '');
      $(jobActiveShow)
        .find(".job-text.location")
        .text($(jobRow).data("cityname") || "");
      $(jobActiveShow)
        .find(".job-text.job-code")
        .text($(jobRow).data("jobcode") || "");
      $(jobActiveShow)
        .find(".job-text.description")
        .html($(jobRow).data("description") || "");
      $(jobActiveShow)
        .find(".job-text.requirements")
        .html($(jobRow).data("requirements") || "");
    } else {
      //$(jobActiveShow).find('.job-text.last-update').text('');
      $(jobActiveShow).find(".job-text.job-code").text("");
      $(jobActiveShow).find(".job-text.location").text("");
      $(jobActiveShow).find(".job-text.description").text("");
      $(jobActiveShow).find(".job-text.requirements").text("");
    }
  }

  function jobActiveShowHandler(el) {
    el.stopImmediatePropagation();
    el.stopPropagation();
    el.preventDefault();
    var row = $(el.target).parents("tr");
    if (row.length < 1) return;

    if ($(row).hasClass("active-tr")) {
      $(row).find("a.btn-table.download.close-details").trigger("click");
      return;
    }

    if (
      !$("tr#job-active-show").is(":visible") ||
      $(jobActiveShow).prev("tr")[0] !== row[0]
    ) {
      // 1. Update details of current job
      setJobDetails(row);

      // 2. Add style class
      $(row).addClass("active-tr").siblings("tr").removeClass("active-tr");

      // 2a.Remove the close button
      $(jobActiveShow)
        .prev("tr")
        .find("a.btn-table.download.close-details")
        .hide()
        .siblings("a")
        .show();

      // 3. Show the details section below the row
      $(jobActiveShow).hide().insertAfter(row).fadeIn(600);

      // 4. Replace the button to close
      $(el.target)
        .parents("tr")
        .find("a.btn-table.download.apply")
        .hide()
        .siblings("a")
        .show();
    }
  }

  function applyJobs(jobs) {
    console.log("Apply jobs:", jobs);
    if (typeof submitPopup === "undefined") return;

    submitPopup.show(jobs);
  }

  function getSelectedJobs() {
    return $('.box-presonal table tr td input[type="checkbox"]:checked')
      .map(function () {
        return this.id;
      })
      .get();
  }

  function jobsApplyWithSelected(activeJob) {
    var activeJobId = activeJob.length > 0 ? activeJob[0].id : null;
    var selectedJobs = getSelectedJobs();

    if (activeJobId && selectedJobs.indexOf(activeJobId) === -1) {
      selectedJobs.push(activeJobId);
    }

    applyJobs(selectedJobs);
  }

  /**** EVENT HANDLERS *****/
  $(document).on("click", "#job-active-show a.btn-table.download", function () {
    var activeJob = $(this)
      .parents("tr")
      .siblings(".active-tr")
      .find('td input[type="checkbox"]');
    jobsApplyWithSelected(activeJob);
  });

  $(document).on("click", "#job-results .apply-job a.apply", function (e) {
    e.preventDefault();
    var activeJob = $(this).parents("tr").find('td input[type="checkbox"]');
    jobsApplyWithSelected(activeJob);
  });

  $(document).on("click", "#apply-jobs", function (e) {
    e.preventDefault();
    var activeJob = []; // empty job
    jobsApplyWithSelected(activeJob);
  });

  $(document).on("click", "#apply-general", function (e) {
    e.preventDefault();
    applyJobs(null);
  });

  $(document).on(
    "click",
    ".apply-job a.btn-table.download.close-details",
    function (e) {
      e.stopImmediatePropagation();
      e.stopPropagation();
      e.preventDefault();
      $(jobActiveShow).hide();
      $(this)
        .hide()
        .siblings("a")
        .show()
        .parents("tr")
        .removeClass("active-tr");
    }
  );

  /**** READY FUNCTION ****/
  $(document).ready(function () {
    jobActiveShow = $("tr#job-active-show");
    $(jobActiveShow).hide();
    $(".apply-job a.btn-table.download.close-details").hide();

    // Handler show active job details
    $(".show-job-details").on("click", jobActiveShowHandler.bind(this));

    // Handler to chenge the state of the checkbox
    $(document).on("change", '.checkbox > input[type="checkbox"]', function () {
      if ($(this).prop("checked")) {
        $(this).parents("tr").addClass("checked-job");
      } else {
        $(this).parents("tr").removeClass("checked-job");
      }
    });
  });
})(jQuery);
