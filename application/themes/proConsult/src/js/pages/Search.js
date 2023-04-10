
export default class SearchListing {
    constructor() {
        this.document = $(document);
        this.window = $(window);
        this.grid = $('.search--list');
        this.loader = $('.loader');
        this.searchInput = $('#keywords');
        this.searchIcon = $('.search-btn-input button');
        this.keywords = $('#keywords');
        this.loadBtn = $('.load--more');
        this.page = 1;
        this.bindEvents();
    }

    bindEvents = () => {
        this.keywords.on('keyup', this.delayLoad);
        this.loadBtn.on('click', this.loadMore);
        // this.document.ready(this.checkKeyword);

    };

    // checkKeyword = () => {
    //     if (!this.getKeywords()) {
    //         this.hideLoader();
    //         let data = `<div class="col-sm-12"><h3>Enter a keyword</h3></div>`;
    //         this.grid.html(data);
    //         return;
    //     }
    //     this.delayLoad();
    // };
    
    delayLoad = (e) => {
        console.log("comes")
        e.preventDefault();
        clearInterval(this.timer);
        // this.scrollToListing();
        this.hideLoadMore();
        // setTimeout(() => {
        //     this.grid.empty();
        // }, 300);
        // this.timer = setTimeout(() => this.filter(), 500);
        this.filter()
    };

  

    

    filter = () => {
        console.log(`${this.getCurrentPagePath()}`);
        this.resetPage();
        this.showLoader();
        $.ajax({
            type: 'GET',
            data: {
                keywords: this.getKeywords(),
                page: this.getPage(),
                isAjax: true
            },
            url: `${this.getCurrentPagePath()}`,
            success: this.applyFilters,
            complete: this.hideLoader()
        });
        this.setURL();
    };

    loadMore = () => {
        this.addPage();
        this.hideLoadMore();
        this.showLoader();
        $.ajax({
            type: 'GET',
            data: {
                keywords: this.getKeywords(),
                page: this.getPage(),
                isAjax: true
            },
            url: `${this.getCurrentPagePath()}`,
            success: this.appendItems,
            complete: this.hideLoader()
        });
    };

    setURL = () => {
        const href = `${this.getCurrentPagePath()}?${$.param({
            keywords: this.getKeywords(),
        })}`;
        window.history.pushState({ href: href }, '', href);
    };

    applyFilters = (response) => {
        let data;
        if (!response) {
            data = `<div class="no-results-msg"><h4>No items match your filters.</h4></div>`;
        } else {
            data = $(response);
            this.showLoadMore();
        }
       
        this.grid.html(data);
        this.grid.prepend(`
        <img src="/proConsult/application/themes/proConsult/dist/images/Union.png" alt="union" class="uni1">
        <img src="/proConsult/application/themes/proConsult/dist/images/Union.png" alt="union" class="uni2">
          
        `);
        //  setTimeout(() => {
        //     const observer = lozad();
        //     observer.observe();
        //   }, 500);
    };

    appendItems = (response) => {
        let data;
        if (response) {
            data = $(response);
            this.grid.append(data);
            this.showLoadMore();
        }
        setTimeout(() => {
            const observer = lozad(); // lazy loads 
            observer.observe();
          }, 500);
    };

    getKeywords = () => {
        return this.keywords.val() ? encodeURI(this.keywords.val()) : '';
    };

    hideLoadMore = () => {
        this.loadBtn.fadeOut();
    };

    showLoadMore = () => {
        this.loadBtn.fadeIn();
    };

    hideLoader = () => {
        this.loader.fadeOut();
    };

    showLoader = () => {
        this.loader.fadeIn();
    };

    addPage = () => {
        this.page += 1;
    };

    resetPage = () => {
        this.page = 1;
    };

    getPage = () => {
        return this.page;
    };

    getCurrentPagePath = () => {
        return location.href.split('?')[0].split('#')[0];
    };
}
