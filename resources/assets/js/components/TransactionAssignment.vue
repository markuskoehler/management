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
                        Unsorted
                    </div>
                    <div class="col-md-2">
                        Personal
                    </div>
                    <div class="col-md-2">
                        Business
                    </div>
                    <div class="col-md-2">
                        Business Invoice
                    </div>
                    <div class="col-md-2">
                        ...
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="row">
            <div class="col-md-3">
                Unsorted
            </div>
            <div class="col-md-3">
                Personal Expense
            </div>
            <div class="col-md-3">
                Business Expense + Invoice
            </div>
            <div class="col-md-3">
                Business Expense w/o Invoice
            </div>
        </div>-->
        <div class="row">
            <div class="col-md-3">Unsorted</div>
            <div class="col-md-3">Personal Expense</div>
            <div class="col-md-3">Business Expense + Invoice</div>
            <div class="col-md-3">Business Expense w/o Invoice</div>
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
        >
            <grid-item v-for="item in testLayout"
                       :key="item.o.id"
                       :x="item.x"
                       :y="item.y"
                       :w="item.w"
                       :h="item.h"
                       :i="item.i"
                       :id="'gi-' + item.o.id">
                <!--<div class="row">
                    <div class="col-md-4 datum">{{ item.o.datum }}</div>
                    <div class="col-md-4 empfaenger">{{ item.o.empfaenger_name }}</div>
                    <div :class="['col-md-4 betrag', item.o.betrag < 0 ? 'negative' : '']">{{ formatMoney(item.o.betrag)
                        }} €
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 valuta">{{ item.o.valuta }}</div>
                    <div class="col-md-4 zweck">{{ item.o.zweck }}{{ item.o.zweck2 }}{{ item.o.zweck3 }}</div>
                    <div class="col-md-4 art">{{ item.o.art }}</div>
                </div>-->
                <div class="row">
                    <div class="col-md-6 datum">{{ item.o.datum }}</div>
                    <div class="col-md-6 valuta">{{ item.o.valuta }}</div>
                </div>
                <div class="row">
                    <div class="col-md-6 empfaenger" :title="item.o.empfaenger_name">{{ item.o.empfaenger_name }}</div>
                    <div :class="['col-md-6 betrag', item.o.betrag < 0 ? 'negative' : '']">{{ formatMoney(item.o.betrag)
                        }} €
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 zweck" :title="item.o.zweck + item.o.zweck2 + item.o.zweck3">{{ item.o.zweck }}{{ item.o.zweck2 }}{{ item.o.zweck3 }}</div>
                    <div class="col-md-6 art" :title="item.o.art">{{ item.o.art }}</div>
                </div>
            </grid-item>
        </grid-layout>
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

                                if (checked) {
                                    vm.selected.accounts.push($(option).val());
                                    console.log('added ' + $(option).val());
                                }
                                else {
                                    let index = vm.selected.accounts.indexOf($(option).val());
                                    //console.log(vm.selected.accounts);
                                    //console.log('to remove ' + $(option).val() + ' from ' + index);
                                    if (index > -1) {
                                        vm.selected.accounts.splice(index, 1);
                                        //console.log('removed ' + $(option).val());
                                    }

                                    if (!vm.selected.accounts.length) return;
                                }

                                if (vm.selected.month !== '') {
                                    // start query
                                    vm.loadTransactions();
                                } else {
                                    // todo clear
                                }
                            }
                        });
                    }
                );
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
                    business: 0,
                    business_invoice: 0
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

                if (this.selected.month !== '' && this.selected.accounts.length) {
                    // if any account selected
                    this.loadTransactions();
                } else {
                    // todo clear
                }
            },
            loadTransactions() {
                this.loading = true;
                axios.get(APP_URL + '/api/transactionassignment?month=' + this.selected.month + '&accounts=' + this.selected.accounts).then((response) => {
                    console.log(response.data);

                    // update stats
                    this.stats.total = response.data.length;

                    // update actual contents
                    this.testLayout = [];
                    $.each(response.data, (i, o) => {
                        this.testLayout.push({"x": 0, "y": i, "w": 1, "h": 1, "i": i, "o": o});
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
            prettifyZweck(value) {
                return this.replaceAll(value, /(EREF\+|CRED\+|)/, '');
            },
            replaceAll(target, search, replacement) {
                return target.replace(new RegExp(search, 'g'), replacement);
            }
        }
    }
</script>

<style>

</style>