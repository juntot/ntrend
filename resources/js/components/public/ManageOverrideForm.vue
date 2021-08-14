<template>
    <div>

            <form method="post">
                <h3 class="text-center form-title"><span class="dblUnderlined">OVERRIDE APPLICATION FORM</span></h3>
                <div class="col-md-12 col-lg-12">
                    <h5 class="form-subtitle"><em>&nbsp;</em></h5>
                </div>
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <select v-model="company" name="company" v-validate="'required'" class="form-field__input" 
                            >
                                <option v-for="comp in compRows" :value="comp.name" :key="comp.id">{{comp.name}}</option>
                            </select>
                            <label class="form-field__label">Company</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('company') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input type="text" :value="'Pending'" name="status"  class="form-field__input">
                            <label class="form-field__label">Status</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('status') }}</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <select v-model="division" name="division" v-validate="'required'" class="form-field__input" 
                            >
                                <option v-for="div in divRows" :value="div.name" :key="div.id">{{div.name}}</option>
                            </select>
                            <label class="form-field__label">Division</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('division') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="dateoverride" name="dateoverride" readonly="true" >
                            <label class="form-field__label">Date Override</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <select v-model="branch" name="branch" v-validate="'required'" class="form-field__input" 
                            >
                                <option v-for="branch in branchRows" :value="branch.name" :key="branch.id">{{branch.name}}</option>
                            </select>
                            <label class="form-field__label">Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('branch') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <select v-model="mode" name="mode" v-validate="'required'" class="form-field__input" 
                            >
                                <option value="PICK-UP">PICK-UP</option>
                                <option value="DELIVERY IN-BASE">DELIVERY IN-BASE</option>
                                <option value="DELIVERY OUT-BASE">DELIVERY OUT-BASE</option>
                            </select>
                            <label class="form-field__label">Mode</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('mode') }}</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input type="text" v-model="customer_name"  v-validate="'required'" name="customer name"  class="form-field__input">
                            <label class="form-field__label">Customer Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('customer name') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                            <div class="form-group-limitx">
                                    <Datepicker :value="commited_date"  wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                    <label slot="afterDateInput" class="form-field__label">Commitment date</label>
                                    <div slot="afterDateInput" class="form-field__bar"></div>
                                    <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                                    </Datepicker>
                            </div>
                </div>
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input type="text" v-model="sales_employee"  v-validate="'required'" name="sales employee"  class="form-field__input">
                            <label class="form-field__label">Sales Employee</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('sales employee') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <select v-model="commited_time" name="commmitment time" v-validate="'required'" class="form-field__input" 
                            >
                                <option value="MORNING">MORNING</option>
                                <option value="AFTERNOON">AFTERNOON</option>
                            </select>
                            <label class="form-field__label">Commitment time</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('commmitment time') }}</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input type="text" v-model="sales_manager"  v-validate="'required'" name="sales manager"  class="form-field__input">
                            <label class="form-field__label">Sales Manager</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('sales manager') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" v-validate="'required'" type="text" class="form-field__input" name="PO/SO" >
                            <label class="form-field__label">PO/SO</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('PO/SO') }}</span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr style="border-top: 8px solid #1F7193">
                <div class="col-md-12 col-lg-12">
                    <h4 class="text-center form-title"><span class="dblUnderlined">REASONS</span></h4>
                </div>
                <div class="col-md-12">
                    <div class="panel-group" id="override-accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading form-subtitle">
                                <h5 class="form-subtitle panel-title">
                                    <a data-toggle="collapse" data-parent="#override-accordion" :href="'#1'">
                                        <em>
                                            <span><i class="far fa-plus-square" style="direction: rtl; font-size: 19px;"></i></span>
                                            ON HOLD
                                        </em>
                                    </a>
                                </h5>
                            </div>
                            <div :id="1" :class="'panel-collapse collapse'">
                                <div class="panel-body">
                                    <div class="col-md-8">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <select v-model="reason" name="reason" v-validate="" class="form-field__input" 
                                                >
                                                    <option value="BOUNCED CHECKS">BOUNCED CHECKS</option>
                                                    <option value="LONG TERM COLLECTION">LONG TERM COLLECTION</option>
                                                    <option value="LONG OVERDUE">LONG OVERDUE</option>
                                                    <option value="DORMANT">DORMANT</option>
                                                </select>
                                                <label class="form-field__label">Reason</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('reason') }}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input type="text" v-model="current_stat"  v-validate="" name="current status"  class="form-field__input">
                                                <label class="form-field__label">Current Status</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('current status') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- OVER DUE -->
                        <div class="panel panel-default">
                            <div class="panel-heading form-subtitle">
                                <h5 class="form-subtitle panel-title">
                                    <a data-toggle="collapse" data-parent="#override-accordion" :href="'#2'">
                                        <em>
                                            <span><i class="far fa-plus-square" style="direction: rtl; font-size: 19px;"></i></span>
                                        OVERDUE
                                        </em>
                                    </a>
                                </h5>
                            </div>
                            <div :id="2" :class="'panel-collapse collapse'">
                                <div class="panel-body">
                                    <br>
                                    <div class="col-md-12">
                                        <div class="mdb-table-overflow">
                                                <table width="100%" class="table table-hover mdb-table" style="margin-bottom: 0">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" width="200px">Invoice#</th>
                                                                <th class="text-center">Invoice Date</th>
                                                                <th class="text-center">Amount</th>
                                                                <th class="text-center">Age</th>
                                                                <th class="text-center" colspan="2">Division</th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="mdb-form-field form-group-limit">
                                                                        <div class="form-field__control form-field--is-filled">
                                                                            <input type="text" name="invoice num" v-model="overdue_fields.invoice_num" class="form-field__input inline-input" >
                                                                            <div class="form-field__bar"></div>
                                                                        </div>
                                                                        <span class="errors">{{ errors.first('invoice num') }}</span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <Datepicker :value="overdue_fields.invoice_date" wrapper-class="mdb-form-field" input-class="form-field__input datePicker inline-input" @selected="''" :typeable="false" :format="'MM/dd/yyyy'">
                                                                        <div slot="afterDateInput" class="form-field__bar"></div>
                                                                    </Datepicker>
                                                                </td>
                                                                <td>
                                                                    <div class="mdb-form-field form-group-limit">
                                                                        <div class="form-field__control form-field--is-filled">
                                                                            <input type="text" name="amount" v-model="overdue_fields.amount" class="form-field__input inline-input" >
                                                                            <div class="form-field__bar"></div>
                                                                        </div>
                                                                        <span class="errors">{{ errors.first('amount') }}</span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="mdb-form-field form-group-limit">
                                                                        <div class="form-field__control form-field--is-filled">
                                                                            <input type="text" name="age" v-model="overdue_fields.age" class="form-field__input inline-input" >
                                                                            <div class="form-field__bar"></div>
                                                                        </div>
                                                                        <span class="errors">{{ errors.first('age') }}</span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="mdb-form-field form-group-limit">
                                                                        <div class="form-field__control form-field--is-filled">
                                                                            <input type="text" name="division" v-model="overdue_fields.division" class="form-field__input inline-input" >
                                                                            <div class="form-field__bar"></div>
                                                                        </div>
                                                                        <span class="errors">{{ errors.first('division') }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="text-right">
                                                                    <button class="btn btn-primary" @click.prevent="appendTable">add</button>
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(item, index) in overdue_tbl" :key="index">
                                                                <td>
                                                                    <span style="padding: 0px 12px;">
                                                                        <a @click="removeRow(index)"><i class="fas fa-trash text-danger"></i></a>
                                                                    </span>
                                                                    {{item.invoice_num}}
                                                                </td>
                                                                <td>
                                                                    {{item.invoice_date}}
                                                                </td>
                                                                <td>
                                                                    {{item.amount}}
                                                                </td>
                                                                <td>
                                                                    {{item.age}}
                                                                </td>
                                                                <td colspan="2">
                                                                    {{item.division}}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                </table>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-lg-12">
                                    <h5 class="form-subtitle"></h5>
                                        <div class="mdb-form-field">
                                            <div class="form-field__control mdb-bgcolor">
                                                <textarea :disabled="$parent.disabledinput" class="form-field__textarea"  cols="4" rows="4" v-validate="" v-model="comment" name="reason & comment"></textarea>
                                                <label class="form-field__label">Reason & Comment</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <h6><span class="errors">{{ errors.first('reason & comment') }}</span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- OVER/NO CREDIT LIMIT -->
                        <div class="panel panel-default">
                            <div class="panel-heading form-subtitle">
                                <h5 class="form-subtitle panel-title">
                                    <a data-toggle="collapse" data-parent="#override-accordion" :href="'#3'">
                                        <em>
                                            <span><i class="far fa-plus-square" style="direction: rtl; font-size: 19px;"></i></span>
                                        OVER/NO CREDIT LIMIT
                                        </em>
                                    </a>
                                </h5>
                            </div>
                            <div :id="3" :class="'panel-collapse collapse'">
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input type="text" v-model="cl"  v-validate="" name="CL"  class="form-field__input">
                                                <label class="form-field__label">CL</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('CL') }}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input type="text" v-model="ar"  v-validate="" name="A/R"  class="form-field__input">
                                                <label class="form-field__label">A/R</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('A/R') }}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input type="text" v-model="pdc"  v-validate="" name="PDC"  class="form-field__input">
                                                <label class="form-field__label">PDC</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('PDC') }}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input type="text" v-model="order"  v-validate="" name="Order"  class="form-field__input">
                                                <label class="form-field__label">ORDER</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('Order') }}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input type="text" v-model="total"  v-validate="" name="Total"  class="form-field__input">
                                                <label class="form-field__label">TOTAL</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('Total') }}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-xs-7 col-sm-7 col-md-3">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input type="text" v-model="excess"  v-validate="" name="excess"  class="form-field__input">
                                                <label class="form-field__label">EXCESS</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('excess') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-5 col-sm-5 col-md-1">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input type="text" :value="100" name="lname"  class="form-field__input">
                                                <label class="form-field__label">%</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input type="text" v-model="last_cl"  v-validate="" name="last CL"  class="form-field__input">
                                                <label class="form-field__label">Last Approved Increase CL</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('last CL') }}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input type="text" v-model="commit_cl"  v-validate="" name="commitment CL"  class="form-field__input">
                                                <label class="form-field__label">Commitment to Increase CL</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('commitment CL') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- UNSETTLED CHECK MOVEMENT -->
                        <div class="panel panel-default">
                            <div class="panel-heading form-subtitle">
                                <h5 class="form-subtitle panel-title">
                                    <a data-toggle="collapse" data-parent="#override-accordion" :href="'#4'">
                                        <em>
                                            <span><i class="far fa-plus-square" style="direction: rtl; font-size: 19px;"></i></span>
                                        UNSETTLED CHECK MOVEMENT
                                        </em>
                                    </a>
                                </h5>
                            </div>
                            <div :id="4" :class="'panel-collapse collapse'">
                                <div class="panel-body">
                                    <br>
                                    <div class="col-md-4">
                                        <div class="group">
                                            <label class="checkbox-lbl mdblbl inline-blocklbl mdblblradio">Account Closed
                                            <input :disabled="$parent.disabledinput" type="checkbox" value="Account Closed" v-model="check_type">
                                            <span class="mdbcheckmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="group">
                                            <label class="checkbox-lbl mdblbl inline-blocklbl mdblblradio">DAIF Check
                                            <input :disabled="$parent.disabledinput" type="checkbox" value="DAIF Check" v-model="check_type">
                                            <span class="mdbcheckmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="group">
                                            <label class="checkbox-lbl mdblbl inline-blocklbl mdblblradio">Puled Out-long Term
                                            <input :disabled="$parent.disabledinput" type="checkbox" value="Puled Out-long Term" v-model="check_type">
                                            <span class="mdbcheckmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="group">
                                            <label class="checkbox-lbl mdblbl inline-blocklbl mdblblradio">Puled Out-Error Details
                                            <input :disabled="$parent.disabledinput" type="checkbox" value="Puled Out-Error Details" v-model="check_type">
                                            <span class="mdbcheckmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mdb-table-overflow">
                                                <table width="100%" class="table table-hover mdb-table" style="margin-bottom: 0">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" width="200px">Bank</th>
                                                                <th class="text-center">Check No</th>
                                                                <th class="text-center">Check Date</th>
                                                                <th class="text-center" colspan="2">Amount</th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="mdb-form-field form-group-limit">
                                                                        <div class="form-field__control form-field--is-filled">
                                                                            <input type="text" name="bank" v-model="check_fields.bank" class="form-field__input inline-input" 
                                                                            v-on:keyup.enter="appendTable2"
                                                                            >
                                                                            <div class="form-field__bar"></div>
                                                                        </div>
                                                                        <span class="errors">{{ errors.first('bank') }}</span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="mdb-form-field form-group-limit">
                                                                        <div class="form-field__control form-field--is-filled">
                                                                            <input type="text" name="checkno" v-model="check_fields.checkno" class="form-field__input inline-input"
                                                                            v-on:keyup.enter="appendTable2"
                                                                            >
                                                                            <div class="form-field__bar"></div>
                                                                        </div>
                                                                        <span class="errors">{{ errors.first('checkno') }}</span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <Datepicker :value="check_fields.check_date" wrapper-class="mdb-form-field" input-class="form-field__input datePicker inline-input" @selected="''" :typeable="false" :format="'MM/dd/yyyy'">
                                                                        <div slot="afterDateInput" class="form-field__bar"></div>
                                                                    </Datepicker>
                                                                </td>
                                                                <td>
                                                                    <div class="mdb-form-field form-group-limit">
                                                                        <div class="form-field__control form-field--is-filled">
                                                                            <input type="text" name="amount" v-model="check_fields.amount" class="form-field__input inline-input" 
                                                                            v-on:keyup.enter="appendTable2"
                                                                            >
                                                                            <div class="form-field__bar"></div>
                                                                        </div>
                                                                        <span class="errors">{{ errors.first('amount') }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="text-right">
                                                                    <button class="btn btn-primary" @click.prevent="appendTable2">add</button>
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(item, index) in check_tbl" :key="index">
                                                            <td>
                                                                <span style="padding: 0px 12px;">
                                                                    <a @click="removeRow2(index)"><i class="fas fa-trash text-danger"></i></a>
                                                                </span>
                                                                {{ item.bank }}
                                                            </td>
                                                            <td>{{ item.checkno }}</td>
                                                            <td>{{ item.check_date }}</td>
                                                            <td colspan="2">{{ item.amount }}</td>
                                                        </tr> 
                                                        </tbody>
                                                </table>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <br>
                                        <div class="mdb-form-field form-group-limit">
                                            <div class="form-field__control">
                                                <input type="text" v-model="paying_habit"  v-validate="" name="paying habit"  class="form-field__input">
                                                <label class="form-field__label">Paying Habit (Past 3 Months)</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('paying habit') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- <div class="col-md-12 col-lg-12">
                    <h5 class="form-subtitle"><em>&nbsp;</em></h5>
                </div> -->
                <hr style="border-top: 8px solid #1F7193">
                <div class="col-lg-12">
                <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                        <div class="form-field__control mdb-bgcolor">
                            <textarea :disabled="$parent.disabledinput" class="form-field__textarea"  cols="4" rows="4" v-validate="" v-model="additional_info" name="additional-info"></textarea>
                            <label class="form-field__label">Additional Information</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                    </div>
                </div>
                <div class="col-lg-12">
                <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                        <div class="form-field__control mdb-bgcolor">
                            <textarea :disabled="$parent.disabledinput" class="form-field__textarea"  cols="4" rows="4" v-validate="" v-model="remarks" name="remarks"></textarea>
                            <label class="form-field__label">Approver Remarks</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <h6><span class="errors">{{ errors.first('remarks') }}</span></h6>
                    </div>
                </div>
                <!-- status is rejected or approve -->
                <div>
                    <!-- Digital Signatures -->
                    <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="computedfullname" name="branch" readonly="true">
                                <label class="form-field__label">Prepared By</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="selected.approvedby_ || ''" name="branch" readonly="true">
                                <label class="form-field__label">
                                    Endorsed By
                                </label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="selected.executedby_ || ''" name="branch" readonly="true">
                                <label class="form-field__label">Approved By</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12" v-if="!$parent.disabledinput">
                    <p class="text-info">
                        LIST OF APPROVERS:
                    </p>
                    <div class="clearfix"></div>
                    <span class="approverlist alert-info" v-for="(approver, key) in $parent.approvers" :key="key">{{approver.approvers}}</span>
                    <br><br>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addOverride" :disabled="isDisable || !isFormValid" v-if="!overrideID && $parent.$data.forapprover != 'approval'">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateUnderTime" :disabled="isDisable || !isFormValid" v-if="overrideID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteUnderTime" :disabled="isDisable" v-if="overrideID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput ">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionUnderTime(1)" v-if="overrideID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel ">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionUnderTime(2)" v-if="overrideID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel">
                    <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionUnderTime(0)" v-if="overrideID && $parent.$data.forapprover == 'approval' && $parent.$data.isCancel">
                </div>
            </form>


    </div>
</template>
<script>
let leavetype = ['Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave', 'Others'];
let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {

        empID_: '',
        dateoverride: moment(new Date()).format('MM/DD/YYYY'),
        mode: '',
        commited_time: '',
        commited_date: moment(new Date()).format('MM/DD/YYYY'),
        po_so: '',
        company: '',
        division: '',
        branch: '',
        customer_name: '',
        sales_employee: '',
        sales_manager: '',
        // reasons section
        reason: '',
        current_stat: '',
        comment: '',

        cl: '',
        ar: '',
        pdc: '',
        order: '',
        total: '',
        excess: '',
        last_cl: '',
        commit_cl: '',
        paying_habit: '',
        check_type: [],
        additional_info: '',
        remarks: '',
        isDisable: false,
        overrideID:'',
        
        approvedby: '',

        // tables
        overdue_tbl: [],
        overdue_fields: {
            invoice_num: '',
            invoice_date: moment(new Date()).format('MM/DD/YYYY'),
            amount: '',
            age: '',
            division: '',
        },
        check_tbl: [],
        check_fields: {
            bank: '',
            checkno: '',
            check_date: moment(new Date()).format('MM/DD/YYYY'),
            amount: '',
        },

        isDisable: false,
        compRows: [],
        divRows: [],
        branchRows: [],
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    methods:{
        addOverride(){
            if(this.isFormValid){
                console.log(this.$data);
                this.isDisable = true;
                let params = this.$data;
                delete params.compRows;
                delete params.divRows;
                delete params.branchRows;
                delete params.check_fields;
                delete params.overdue_fields;
                // params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addoverride', params).then((response)=>{
                //     this.$parent.addRow(response.data);
                    this.isDisable = false;
                }).catch((err)=>{console.log(err);});
            }
        },
        appendTable(){
            const tbl = Object.values(this.overdue_fields);
            if(!tbl.includes('')) {
                this.overdue_tbl.unshift(this.overdue_fields);
                this.overdue_fields ={
                    invoice_num: '',
                    invoice_date: moment(new Date()).format('MM/DD/YYYY'),
                    amount: '',
                    age: '',
                    division: '',
                };
            }
        },
        appendTable2(){
            const tbl = Object.values(this.check_fields);
            if(!tbl.includes('')) {
                
                this.check_tbl.unshift(this.check_fields);
                this.check_fields = {
                    bank: '',
                    checkno: '',
                    check_date: moment(new Date()).format('MM/DD/YYYY'),
                    amount: '',
                };
            }
        },
        removeRow(index)
        {
            this.overdue_tbl.splice(index, 1);
        },
        removeRow2(index)
        {
            this.check_tbl.splice(index, 1);
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{

                if(key != 'compRows' && key != 'divRows' && key != 'branchRows'
                    
                ) {
                    this.$data[key] =  '';
                }
                
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
                if(key == 'dateoverride' || key == 'commited_date'){
                    this.dateoverride = moment(new Date()).format('MM/DD/YYYY');
                    this.commited_date = moment(new Date()).format('MM/DD/YYYY');
                }
            });
            $("#myModal").modal("hide");
        },
        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
                if(i == 'leavetype')
                    this.$data[i] = (leavetype.indexOf(data[i]) + 1);

            }

            this.MDBINPUT();
            $("#myModal").modal("show");
        },
        MDBINPUT(){
            this.$nextTick(() => {
                [].forEach.call(
                        document.querySelectorAll('.form-field__input, .form-field__textarea'),
                        (el) => {
                            el.onblur = () => {
                                setActive(el, false)
                            }
                            el.onfocus = () => {
                                setActive(el, true)
                            }
                            if(el.value !='')
                            {
                                // console.log(el);
                                if(!el.parentNode.classList.contains('form-field__control')){
                                    el.parentNode.classList.add('form-field__control');
                                }
                                el.parentNode.parentNode.classList.add('form-field--is-filled');
                            }
                        }
                    );
             });
        }
    },
    computed:{
        isFormValid(){
            return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },

       
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
        computedposname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.posname  : this.userinfo.posname;
        },
        computedbranchname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.branchname  : this.userinfo.branchname;
        }
    },

    created(){
        VeeValidate.Validator.extend('is_time', {
            getMessage: field => `The format must be HH:MM AM/PM`,
            validate: (value) => new Promise(resolve => {
                // let regex = new RegExp("([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])");
                // let regex = new RegExp("^(1[0-2]|0?[1-9]):[0-5][0-9] (AM|PM)$");
                let regex = new RegExp("^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9])|(24:?00))");
                resolve({
                    valid: value && regex.test(value)
                });
            })
        });

        axios.get('api/get-override-company')
        .then(({data})=>{
            data.forEach( val => {
                if(val.type == 'company') {
                    this.compRows = JSON.parse(val.json);
                }
                else if(val.type == 'division') {
                    this.divRows = JSON.parse(val.json)
                }
                else {
                    this.branchRows = JSON.parse(val.json);
                }
            });
        });
    },
    beforeDestroy(){
        bus.$off('setupdate', this.test)
    },
    mounted(){

        this.MDBINPUT();
        if(this.$parent.disabledinput){
            $('.vdp-datepicker div.vdp-datepicker__calendar:nth-child(2)').addClass('disable-dates-approver');
        }
        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);
        // EVENT BUS
        bus.$on('setupdate', this.setDataForEdit);

        // accordion
        $('#override-accordion')
        .on('shown.bs.collapse', function() {
            $('.collapse.in').prev().find("span").html('<i class="far fa-minus-square" style="direction: rtl; font-size: 19px;"></i>');
        }).on('hide.bs.collapse', function() {
            $('.collapse.in').prev().find("span").html('<i class="far fa-plus-square" style="direction: rtl; font-size: 19px;"></i>');
        });

    }
}

    const setActive = (el, active) => {
        const formField = el.parentNode.parentNode
        if (active) {
            formField.classList.add('form-field--is-active')
        } else {
            formField.classList.remove('form-field--is-active')
            el.value === '' ?
            formField.classList.remove('form-field--is-filled') :
            formField.classList.add('form-field--is-filled')
        }
    }
</script>