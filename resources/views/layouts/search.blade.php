<div id="global-search-wrapper" style="position:relative; display:none">
    <div style="position:fixed; top:0; left:0; width:100%; height:100%;
                background:rgba(0,0,0,0.5); z-index:9998"
         id="search-overlay" onclick="closeSearch()">
    </div>
    <div style="position:fixed; top:80px; left:50%; transform:translateX(-50%);
                width:600px; max-width:95vw; z-index:9999; background:white;
                border-radius:8px; box-shadow:0 10px 30px rgba(0,0,0,0.3)">
        <div style="padding:16px; border-bottom:1px solid #eee">
            <div style="display:flex; align-items:center; gap:10px">
                <i class="fas fa-search text-primary fa-lg"></i>
                <input type="text"
                       id="global-search-input"
                       placeholder="Rechercher par CIN, CEF, Nom, Prénom..."
                       style="border:none; outline:none; font-size:16px; flex:1"
                       autocomplete="off">
                <button onclick="closeSearch()"
                        style="border:none; background:none; font-size:18px; cursor:pointer; color:#999">
                    &times;
                </button>
            </div>
        </div>
        <div id="search-results" style="max-height:400px; overflow-y:auto; padding:8px">
            <p class="text-muted text-center py-3 mb-0" id="search-hint">
                <i class="fas fa-keyboard"></i> Tapez au moins 2 caractères...
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        openSearch();
    }
    if (e.key === 'Escape') closeSearch();
});

document.addEventListener('DOMContentLoaded', function() {
    var searchBtn = document.querySelector('[data-widget="navbar-search"]');
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            openSearch();
        });
    }
});

function openSearch() {
    var w = document.getElementById('global-search-wrapper');
    if (!w) return;
    w.style.display = 'block';
    var input = document.getElementById('global-search-input');
    if (input) setTimeout(function() { input.focus(); }, 100);
}

function closeSearch() {
    var w = document.getElementById('global-search-wrapper');
    if (!w) return;
    w.style.display = 'none';
    var input = document.getElementById('global-search-input');
    if (input) input.value = '';
    var results = document.getElementById('search-results');
    if (results) {
        results.innerHTML =
            '<p class="text-muted text-center py-3 mb-0"><i class="fas fa-keyboard"></i> Tapez au moins 2 caractères...</p>';
    }
}

var searchTimer;
document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('global-search-input');
    if (!input) return;
    input.addEventListener('input', function() {
        clearTimeout(searchTimer);
        var q = this.value.trim();
        var results = document.getElementById('search-results');
        if (!results) return;

        if (q.length < 2) {
            results.innerHTML =
                '<p class="text-muted text-center py-3 mb-0"><i class="fas fa-keyboard"></i> Tapez au moins 2 caractères...</p>';
            return;
        }

        results.innerHTML =
            '<p class="text-center py-3 mb-0"><i class="fas fa-spinner fa-spin"></i> Recherche...</p>';

        searchTimer = setTimeout(function() {
            fetch('/search?q=' + encodeURIComponent(q), {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.length === 0) {
                    results.innerHTML =
                        '<p class="text-muted text-center py-3 mb-0"><i class="fas fa-search"></i> Aucun résultat</p>';
                    return;
                }
                var html = '';
                data.forEach(function(t) {
                    html +=
                        '<a href="' + t.url + '" onclick="closeSearch()" ' +
                        'style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:6px;' +
                        'color:inherit;text-decoration:none;border-bottom:1px solid #f0f0f0" ' +
                        'onmouseover="this.style.background=\'#f8f9fa\'" onmouseout="this.style.background=\'none\'">' +
                        '<div style="width:40px;height:40px;border-radius:50%;background:' +
                        (t.validated ? '#28a745' : '#6c757d') +
                        ';display:flex;align-items:center;justify-content:center;color:white;' +
                        'font-weight:bold;flex-shrink:0;font-size:16px">' +
                        String(t.name).charAt(0) + '</div>' +
                        '<div style="flex:1">' +
                        '<div style="font-weight:500">' + t.name + '</div>' +
                        '<div style="font-size:12px;color:#6c757d">CIN: ' + t.cin + ' | CEF: ' + t.cef + ' | ' + t.filiere + '</div>' +
                        '</div>' +
                        '<div style="text-align:right;flex-shrink:0">' +
                        (t.validated
                            ? '<span style="background:#d4edda;color:#155724;padding:2px 8px;border-radius:12px;font-size:11px"><i class="fas fa-check"></i> Validé</span>'
                            : '<span style="background:#e2e3e5;color:#383d41;padding:2px 8px;border-radius:12px;font-size:11px">En cours</span>') +
                        '<div style="font-size:11px;color:#6c757d;margin-top:2px">' + t.docs_count + ' doc(s)</div>' +
                        '</div></a>';
                });
                results.innerHTML = html;
            });
        }, 300);
    });
});
</script>
