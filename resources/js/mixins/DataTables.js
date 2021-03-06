export default {
    props: {
        fetchUrl: { type: String, required: true },
        columns: { type: Array, required: true },
    },

    data() {
        return {
            tableData: [],
            url: "",
            pagination: {
                meta: { to: 1, from: 1 }
            },
            offset: 2,
            currentPage: 1,
            perPage: 10,
            order: "asc",
            loading: false
        };
    },

    watch: {
        fetchUrl: {
            handler(fetchUrl) {
                this.url = fetchUrl;
            },
            immediate: true
        }
    },

    created() {
        this.fetchData();

        window.events.$on("fetchData", () => {
            this.fetchData();
        });
    },

    computed: {
        /**
         * Get the pages number array for displaying in the pagination.
         * */
        pagesNumber() {
            if (!this.pagination.meta.to) {
                return [];
            }

            let from = this.pagination.meta.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            let to = from + this.offset * 2;
            if (to >= this.pagination.meta.last_page) {
                to = this.pagination.meta.last_page;
            }

            let pagesArray = [];
            for (let page = from; page <= to; page++) {
                pagesArray.push(page);
            }

            return pagesArray;
        }
    },

    methods: {
        async fetchData() {
            let dataFetchUrl = `${this.url}?page=${this.currentPage}&column=${this.columns[0]
                }&order=${this.order}&per_page=${this.perPage}`;

            this.loading = true;

            await axios
                .get(dataFetchUrl)
                .then(({ data }) => {
                    this.loading = false;
                    this.pagination = data;
                    this.tableData = data.data;
                })
                .catch(error => (this.tableData = []));
        },

        /**
         * Change the page.
         * @param pageNumber
         */
        changePage(pageNumber) {
            this.currentPage = pageNumber;
            this.fetchData();
        },
    }
}