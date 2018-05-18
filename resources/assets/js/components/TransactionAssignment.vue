<template>
    <div>
        <loading :active.sync="loading" :can-cancel="false"></loading>
        <div class="row">
            <!-- filter -->
            <form>
                <div class="col-md-3 col-md-offset-3 text-center">
                    <div class="form-group">
                        <label for="filter-month">Monat</label>
                        <input type="month" class="form-control" id="filter-month" v-model="selected.month"
                               v-on:change="monthChange">
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="form-group">
                        <label for="filter-account">Konto</label><br>
                        <select multiple class="form-control" id="filter-account">
                            <option v-for="account in accounts" :value="account.id">
                                {{ account.bezeichnung }}
                            </option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <!-- statistics -->
                    <div class="col-md-2">
                        <strong>Total</strong><br>
                        {{ stats.total }}
                    </div>
                    <div class="col-md-2">
                        <strong>Unsorted</strong><br>
                        {{ stats.unsorted }}<br>
                        {{ +(stats.unsorted / stats.total * 100).toFixed(2) || 0 }} %
                    </div>
                    <div class="col-md-2">
                        <strong>Personal</strong><br>
                        {{ stats.personal }}<br>
                        {{ +(stats.personal / stats.total * 100).toFixed(2) || 0 }} %
                    </div>
                    <div class="col-md-2">
                        <strong>Business Invoice</strong><br>
                        {{ stats.business_invoice }}<br>
                        {{ +(stats.business_invoice / stats.total * 100).toFixed(2) || 0 }} %
                    </div>
                    <div class="col-md-2">
                        <strong>Business</strong><br>
                        {{ stats.business }}<br>
                        {{ +(stats.business / stats.total * 100).toFixed(2) || 0 }} %
                    </div>
                    <div class="col-md-2">
                        <strong>...</strong><br>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3" v-for="account in accounts">
                <span :class="['circle', 'legend', stringToAlphaNumeric(account.bezeichnung)]"></span>
                {{ account.bezeichnung }}
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-3"><strong>Unsorted</strong></div>
            <div class="col-md-3"><strong>Personal Expense</strong></div>
            <div class="col-md-3"><strong>Business Expense + Invoice</strong></div>
            <div class="col-md-3"><strong>Business Expense w/o Invoice</strong></div>
        </div>

        <grid-layout
                :layout="testLayout"
                :col-num="4"
                :row-height="60"
                :is-draggable="true"
                :is-resizable="false"
                :is-mirrored="false"
                :vertical-compact="true"
                :margin="[10, 10]"
                :use-css-transforms="true"
                @layout-updated="layoutUpdatedEvent"
                v-show="stats.total"
        >
            <grid-item v-for="item in testLayout"
                       :key="item.o.id"
                       :x="item.x"
                       :y="item.y"
                       :w="item.w"
                       :h="item.h"
                       :i="item.i"
                       :id="'gi-' + item.o.id"
                       :class="stringToAlphaNumeric(item.o.account.bezeichnung)"
                       @moved="movedEvent">
                <i class="fa fa-exclamation-triangle" aria-hidden="true" v-if="item.o.todo"></i>
                <div class="row">
                    <div class="col-md-6 datum">{{ (new Date(item.o.datum)).toLocaleDateString("de-DE") }}</div>
                    <div class="col-md-6 valuta">{{ (new Date(item.o.valuta)).toLocaleDateString("de-DE") }}</div>
                </div>
                <div class="row">
                    <div class="col-md-6 empfaenger" :title="item.o.empfaenger_name">{{ item.o.empfaenger_name }}</div>
                    <div :class="['col-md-6 betrag', item.o.betrag < 0 ? 'negative' : '']">{{ formatMoney(item.o.betrag)
                        }} €
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 zweck"
                         :title="item.o.zweck + ' ' + item.o.zweck2 + ' ' + item.o.zweck3 | prettifyZweck">{{
                        item.o.zweck | prettifyZweck }} {{ item.o.zweck2 | prettifyZweck }} {{ item.o.zweck3 |
                        prettifyZweck }}
                    </div>
                    <div class="col-md-6 art" :title="item.o.art">{{ item.o.art }}</div>
                </div>
            </grid-item>
        </grid-layout>
        <div class="text-center" v-if="!stats.total">No records found</div>
    </div>
</template>

<script>
    export default {
        mounted() {
            //console.log('Component mounted.');

            axios.get(APP_URL + '/api/transactionassignment/accounts').then((response) => {
                this.accounts = response.data;

                const vm = this;

                Vue.nextTick(
                    () => {
                        $('#filter-account').multiselect({
                            onChange: function (option, checked, select) {
                                //console.log('Changed option ' + $(option).val() + '.' + checked);

                                vm.resetStats();

                                if (checked) {
                                    vm.selected.accounts.push($(option).val());
                                    //console.log('added ' + $(option).val());
                                }
                                else {
                                    let index = vm.selected.accounts.indexOf($(option).val());
                                    //console.log(vm.selected.accounts);
                                    //console.log('to remove ' + $(option).val() + ' from ' + index);
                                    if (index > -1) {
                                        vm.selected.accounts.splice(index, 1);
                                        //console.log('removed ' + $(option).val());
                                    }

                                    if (!vm.selected.accounts.length) {
                                        vm.testLayout = [];
                                        return;
                                    }
                                }

                                if (vm.selected.month !== '') {
                                    // start query
                                    vm.loadTransactions();
                                } else {
                                    vm.testLayout = [];
                                }
                            }
                        });
                    }
                );

                // store class name and color per account
                $.each(vm.accounts, function (i, o) {
                    const name = vm.stringToAlphaNumeric(o.bezeichnung);
                    const clr = vm.stringToColor(o.iban + o.bezeichnung + Math.random());
                    $('<style>div.' + name + '{border-color:' + clr + ';} span.' + name + '{background-color:' + clr + '}</style>').appendTo('head');
                });
            }).catch((error) => {
                console.error(error);
                alert(error);
            }).finally(() => {
                this.loading = false;
            });
        },
        data() {
            return {
                loading: true,
                accounts: [],
                selected: {
                    accounts: [],
                    month: '',
                },
                stats: {
                    total: 0,
                    unsorted: 0,
                    personal: 0,
                    business_invoice: 0,
                    business: 0,
                },
                testLayout: [
                    /*{"x": 0, "y": 0, "w": 1, "h": 1, "i": "0"},
                    {"x": 1, "y": 0, "w": 1, "h": 1, "i": "1"},
                    {"x": 2, "y": 0, "w": 1, "h": 1, "i": "2"},
                    {"x": 3, "y": 0, "w": 1, "h": 1, "i": "3"},
                    /*{"x":2,"y":0,"w":2,"h":4,"i":"1"},
                    {"x":4,"y":0,"w":2,"h":5,"i":"2"},
                    {"x":6,"y":0,"w":2,"h":3,"i":"3"},
                    {"x":8,"y":0,"w":2,"h":3,"i":"4"},
                    {"x":10,"y":0,"w":2,"h":3,"i":"5"},
                    {"x":0,"y":5,"w":2,"h":5,"i":"6"},
                    {"x":2,"y":5,"w":2,"h":5,"i":"7"},
                    {"x":4,"y":5,"w":2,"h":5,"i":"8"},
                    {"x":6,"y":4,"w":2,"h":4,"i":"9"},
                    {"x":8,"y":4,"w":2,"h":4,"i":"10"},
                    {"x":10,"y":4,"w":2,"h":4,"i":"11"},
                    {"x":0,"y":10,"w":2,"h":5,"i":"12"},
                    {"x":2,"y":10,"w":2,"h":5,"i":"13"},
                    {"x":4,"y":8,"w":2,"h":4,"i":"14"},
                    {"x":6,"y":8,"w":2,"h":4,"i":"15"},
                    {"x":8,"y":10,"w":2,"h":5,"i":"16"},
                    {"x":10,"y":4,"w":2,"h":2,"i":"17"},
                    {"x":0,"y":9,"w":2,"h":3,"i":"18"},
                    {"x":2,"y":6,"w":2,"h":2,"i":"19"}*/
                ]
            }
        },
        methods: {
            monthChange(e) {
                //console.log('month changed to ');
                //console.log(e.target.value);
                this.resetStats();

                if (this.selected.month !== '' && this.selected.accounts.length) {
                    // if any account selected
                    this.loadTransactions();
                } else {
                    this.testLayout = [];
                }
            },
            loadTransactions() {
                this.loading = true;
                axios.get(APP_URL + '/api/transactionassignment?month=' + this.selected.month + '&accounts=' + this.selected.accounts).then((response) => {
                    //console.log(response.data);

                    // update actual contents
                    this.testLayout = [];
                    $.each(response.data, (i, o) => {
                        let category = this.randomIntFromInterval(0,3);//o.category;
                        this.testLayout.push({"x": category, "y": i, "w": 1, "h": 1, "i": o.id, "o": o});
                    });

                    // calculate stats
                    this.resetStats();

                    this.stats.total = response.data.length;

                    const vm = this;

                    $.each(this.testLayout, function(i, o) {
                        switch(o.x) {
                            case 0:
                                vm.stats.unsorted++;
                                break;
                            case 1:
                                vm.stats.personal++;
                                break;
                            case 2:
                                vm.stats.business_invoice++;
                                break;
                            case 3:
                                vm.stats.business++;
                                break;
                            default:
                                throw 'Error!';
                        }
                    });
                }).catch((error) => {
                    console.error(error);
                }).finally(() => {
                    this.loading = false;
                });
            },
            formatMoney(n, c, d, t) {
                var c = isNaN(c = Math.abs(c)) ? 2 : c,
                    d = d == undefined ? "," : d,
                    t = t == undefined ? "." : t,
                    s = n < 0 ? "- " : "",
                    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                    j = (j = i.length) > 3 ? j % 3 : 0;
                return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
            },
            /*hashCode(str) { // java String#hashCode
                var hash = 0;
                for (var i = 0; i < str.length; i++) {
                    hash = str.charCodeAt(i) + ((hash << 5) - hash);
                }
                return hash;
            },
            intToRGB(i) {
                var c = (i & 0x00FFFFFF)
                    .toString(16)
                    .toUpperCase();

                return "00000".substring(0, 6 - c.length) + c;
            }*/
            stringToColor(str) {
                var hash = 0;
                for (var i = 0; i < str.length; i++) {
                    hash = str.charCodeAt(i) + ((hash << 5) - hash);
                }
                var colour = '#';
                for (var i = 0; i < 3; i++) {
                    var value = (hash >> (i * 8)) & 0xFF;
                    colour += ('00' + value.toString(16)).substr(-2);
                }
                return colour;
            },
            stringToAlphaNumeric(input) {
                return input.replace(/\W/g, '');
            },
            randomIntFromInterval(min, max) {
                return Math.floor(Math.random() * (max - min + 1) + min);
            },
            layoutUpdatedEvent(newLayout) {
                console.log("Updated layout: ", newLayout)
            },
            movedEvent(i, newX, newY) {
                console.log("MOVED i=" + i + ", X=" + newX + ", Y=" + newY);

                // re-calculate stats
                this.resetStats();



                // todo just for testing
                let pos = this.testLayout.map(function(e) { return e.i; }).indexOf(i);
                if(newX == 3) {
                    this.testLayout[pos].o.todo = true;
                } else {
                    this.testLayout[pos].o.todo = false;
                }
            },
            resetStats() {
                this.stats.total = 0;
                this.stats.unsorted = 0;
                this.stats.personal = 0;
                this.stats.business_invoice = 0;
                this.stats.business = 0;
            }
        },
        filters: {
            prettifyZweck(value) {
                //console.log(value);
                if (value === null) return;
                return value.replace(new RegExp(/(ABWA\+|EREF\+|MREF\+|CRED\+|SVWZ\+|null)/, 'g'), '');
            },
        }
    }
</script>

<style>

</style>