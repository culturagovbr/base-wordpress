(function ($, undefined) {
    var URL = "https://api.github.com/orgs/#{org}/repos?per_page=10&page=1";
    var organizations = softwareCatalogJS.organizations;
    var PROJECT = '<div class="software-catalog-project hide">' +
                   '    <div class="container-inner">' +
                   '      <h2><img class="project-avatar" src="#{avatar}&s=35" alt="avatar">#{title}</h2>' +
                   '      <p class="description">#{description}</p>' +
                   '      <span class="language">#{language}</span>' +
                   '      <a class="btn" href="#{html_url}" target="_blank">Detalhes &raquo;</a>' +
                   '    </div>' +
                   '</div>';
    var MEMBER = '<li class="software-catalog-member hide">' +
                   '    <img class="photo" src="#{url}" width="50" height="50">' +
                   '    <a class="url" href="#{url}" target="_blank">@#{login}</a>' +
                   '</li>';
    var AJAX = [];

    var ajaxWithURI = function(uri) {
      return $.ajax({
        dataType: "json",
        url: uri,
        beforeSend: function(xhr) {
          xhr.uri = uri;
        }
      });
    };

    var parseLinkNext = function(xhr) {
      var links = xhr.getResponseHeader("Link");
      if (!links) {
        return null;
      }
      var nextMatch = links.match(/<(.*?)>; rel="next"/);
      if (nextMatch && nextMatch[1]) {
        return nextMatch[1];
      }
      return null;
    };

    for (var i = 0; i < organizations.length; i++) {
      var uri = URL.replace("#{org}", organizations[i]);
      AJAX.push(ajaxWithURI(uri));
    }

    var repos = [];
    var onRepos = function() {
      var rendered_repos = "";
      var args = arguments;
      var i;

      AJAX = [];

      if (!$.isArray(args[0][0])) {
        args = [args];
      }

      for ( i = 0, len = args.length; i < len; i++ ) {
        var githubObj = args[i][0],
            status = args[i][1],
            xhr = args[i][2];
        if (status !== "success") {
          xhr = args[i][0];
          if (console && console.log) {
            console.log("Failed to fetch " + xhr.uri + ", ignoring.");
          }
          continue;
        }
        if (!githubObj || githubObj.length === 0) {
          continue;
        }
        repos = repos.concat(githubObj);
        var next = parseLinkNext(xhr);
        if (next) {
          AJAX.push(ajaxWithURI(next));
        }
      }

      if (AJAX.length > 0) {
        $.when.apply($, AJAX).always(onRepos);
        return;
      }

      repos.sort(function (a, b) { return b.stargazers_count - a.stargazers_count; });

      for ( i = 0; i < repos.length; i++ ) {
        console.log(repos[i]);
        if (repos[i].name !== "IWantToWorkAtGloboCom") {
          rendered_repos += PROJECT.replace("#{title}", repos[i].name)
                          .replace("#{avatar}", repos[i].owner.avatar_url)
                          .replace("#{description}", repos[i].description)
                          .replace("#{language}", repos[i].language !== 'null' ? repos[i].language : '' )
                          .replace("#{html_url}", repos[i].html_url);
        }
      }

      $(".repos").empty();
      $(".repos").append(rendered_repos);
      $(".project").fadeIn(1000);
    };

    $.when.apply($, AJAX).always(onRepos);

    var members = [];
    var onMembers = function(githubData, status, xhr) {
      if (status !== "success") {
        xhr = githubData;
        if (console && console.log) {
          console.log("Failed to fetch " + xhr.uri + ", ignoring.");
        }
        return;
      }
      if (!githubData || githubData.length === 0) {
        return;
      }
      members = members.concat(githubData);

      var next = parseLinkNext(xhr);
      if (next) {
        ajaxWithURI(next).always(onMembers);
        return;
      }

      members.sort(function (a, b) { return a.login.localeCompare(b.login); });
      var membersRendered = "";
      for (var i = 0; i < members.length; i++) {
          membersRendered += MEMBER.replace("#{url}", members[i].avatar_url)
                                   .replace("#{login}", members[i].login)
                                   .replace("#{url}", members[i].url
                                   .replace("api.", "")
                                   .replace("users/", ""));
      }
      $(".members").empty().append(membersRendered);
      $(".member").fadeIn(1000);
    };

    var team = softwareCatalogJS.team;
    ajaxWithURI("https://api.github.com/orgs/"+ team +"/members?page=1&per_page=100").always(onMembers);
})(jQuery);
