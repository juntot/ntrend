<template>
    <div>

            <form method="post">
                <h3 class="text-center form-title"><span class="dblUnderlined">OVERRIDE APPLICATION FORM</span></h3>
                <div class="col-md-12 col-lg-12">
                    <h5 class="form-subtitle"><em>&nbsp;</em></h5>
                </div>
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="relative-pos">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" :value="company"  v-validate="'required'" name="company"  class="form-field__input"
                                @focus="compFocus = true"
                                @blur="handleBlur"
                                >
                                <label class="form-field__label">Company</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('company') }}</span>
                            <!-- loader and err msg -->
                            <div v-if="loader2">
                                <i class="fas fa-spinner fa-spin"></i> 
                                <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                            </div>
                            <div class="absolute-pos bg-white suggestion_filter" 
                                v-if="compFocus">
                                <ul>
                                    <li v-for="comp in compRows" 
                                    @click.prevent="selectCompany(comp)"
                                    :key="comp.id">
                                        {{comp.name}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" :value="computedStatus" name="status"  class="form-field__input">
                            <label class="form-field__label">Status</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('status') }}</span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <!-- <select :disabled="true" v-model="branch" name="branch" v-validate="'required'" readonly="true" class="form-field__input" 
                            >
                                <option v-for="branch in branchRows" :value="branch.name" :key="branch.id">{{branch.name}}</option>
                            </select> -->
                            <input :disabled="true" type="text" :value="branch" name="branch"  v-validate="'required'" class="form-field__input">
                            <label class="form-field__label">Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors" v-if="errBranch">{{ 'Unable to get branch name' }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" :value="division" name="division"  v-validate="'required'" class="form-field__input">
                            <label class="form-field__label">Division</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('division') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" :value="dateoverride" name="dateoverride" readonly="true" >
                            <label class="form-field__label">Date & Time Override</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="relative-pos">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" v-model="search_customer" 
                                    @keyup.prevent="getCustomer"
                                    v-validate="'required'" name="customer name"  class="form-field__input">
                                <label class="form-field__label">Customer Name</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span v-if="!search_customer" class="errors">
                                {{ search_customer && !customerList.length ? 
                                    'customer not found':''||  errors.first('customer name') }}
                            </span>
                            <div class="absolute-pos bg-white suggestion_filter" 
                                v-if="search_customer && !customer_name && customerList.length > 0 || loader  && !customer_name
                            ">
                                <!-- loader and err msg -->
                                <div v-if="loader" style="padding-left: 8px">
                                    <i class="fas fa-spinner fa-spin"></i> 
                                    <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                                </div>
                                <ul>
                                    <li v-for="customer in customerList" 
                                    @click.prevent="selectedCustomer(customer)"
                                    :key="customer.CardCode">
                                        {{customer.CardName}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <select :disabled="true" v-model="mode" name="mode" v-validate="'required'" class="form-field__input" 
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
                        <div class="relative-pos">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" v-model="search_employee"  v-validate="'required'" name="sales employee"  class="form-field__input"
                                @keyup.prevent="searchEmployee"
                                >
                                <label class="form-field__label">Sales Employee</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors" v-if="!search_employee">{{ errors.first('sales employee') }}</span>
                            <div class="absolute-pos bg-white suggestion_filter" 
                                v-if="search_employee && !sales_employee && employeeList.length > 0
                            ">
                                <!-- loader and err msg -->
                                <div v-if="loader">
                                    <i class="fas fa-spinner fa-spin"></i> 
                                    <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                                </div>
                                <ul>
                                    <li v-for="emp in employeeList" 
                                    @click.prevent="selectedEmployee(emp.fullname)"
                                    :key="emp.empID">
                                        {{emp.fullname}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="col-md-4">
                            <div class="form-group-limitx">
                                    <Datepicker :disabled="true" :value="commited_date"  wrapper-class="mdb-form-field" 
                                    @selected="selectDateCommitment"
                                    input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                    <label slot="afterDateInput" class="form-field__label">Commitment date</label>
                                    <div slot="afterDateInput" class="form-field__bar"></div>
                                    <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                                    </Datepicker>
                            </div>
                </div>
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="relative-pos">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" v-model="search_manager"  v-validate="'required'" name="sales manager"  class="form-field__input"
                                @keyup.prevent="searchManager"
                                >
                                <label class="form-field__label">Sales Manager</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors" v-if="!search_manager">{{ errors.first('sales manager') }}</span>
                            <div class="absolute-pos bg-white suggestion_filter" 
                                v-if="search_manager && !sales_manager && employeeList.length > 0
                            ">
                                <!-- loader and err msg -->
                                <div v-if="loader">
                                    <i class="fas fa-spinner fa-spin"></i> 
                                    <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                                </div>
                                <ul>
                                    <li v-for="emp in employeeList" 
                                    @click.prevent="selectedEmployee(emp.fullname, 'manager')"
                                    :key="emp.empID">
                                        {{emp.fullname}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <select :disabled="true" v-model="commited_time" name="commmitment time" v-validate="'required'" class="form-field__input" 
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
                            <input :disabled="true" v-validate="'required'" type="text" v-model="amount_order" class="form-field__input" name="amount of order" >
                            <label class="form-field__label">Amount of Order</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('amount of order') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="true" v-validate="'required'" type="text" v-model="po_so" class="form-field__input" name="PO/SO" >
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
                                                <select :disabled="true" v-model="reason" name="reason" v-validate="" class="form-field__input" 
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
                                    <div class="col-md-8">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input :disabled="true" type="text" v-model="current_stat"  v-validate="" name="current status"  class="form-field__input">
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
                                                                <th class="text-center" style="
                                                                    min-width: 89px; max-width: 89px !important;
                                                                    width: 89px;
                                                                ">
                                                                    Invoice#
                                                                </th>
                                                                <th class="text-center">Invoice Date</th>
                                                                <th class="text-center">Amount</th>
                                                                <th class="text-center" style="
                                                                    min-width: 89px; max-width: 89px !important;
                                                                    width: 89px;
                                                                ">Age</th>
                                                                <th class="text-center" colspan="2">Division</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(item, index) in overdue_tbl" :key="index">
                                                                <td>
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
                                                <textarea :disabled="true" class="form-field__textarea"  cols="4" rows="4" v-validate="" v-model="comment" name="reason & comment"></textarea>
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
                                    <div class="col-xs-7 col-sm-7 col-md-3">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input :disabled="true" type="text" v-model="excess"  v-validate="" name="excess"  class="form-field__input">
                                                <label class="form-field__label">EXCESS</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('excess') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-5 col-sm-5 col-md-2">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input :disabled="true" type="text" v-model="percent" name="percent"  class="form-field__input">
                                                <label class="form-field__label">% (ex: 100)</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-5">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input :disabled="true" type="text" v-model="last_cl"  v-validate="" name="last CL"  class="form-field__input">
                                                <label class="form-field__label">Last Approved Increase CL</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('last CL') }}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-5">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input :disabled="true" type="text" v-model="commit_cl"  v-validate="" name="commitment CL"  class="form-field__input">
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
                                            <input :disabled="true" type="checkbox" value="Account Closed" v-model="check_type">
                                            <span class="mdbcheckmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="group">
                                            <label class="checkbox-lbl mdblbl inline-blocklbl mdblblradio">DAIF Check
                                            <input :disabled="true" type="checkbox" value="DAIF Check" v-model="check_type">
                                            <span class="mdbcheckmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="group">
                                            <label class="checkbox-lbl mdblbl inline-blocklbl mdblblradio">Puled Out-long Term
                                            <input :disabled="true" type="checkbox" value="Puled Out-long Term" v-model="check_type">
                                            <span class="mdbcheckmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="group">
                                            <label class="checkbox-lbl mdblbl inline-blocklbl mdblblradio">Puled Out-Error Details
                                            <input :disabled="true" type="checkbox" value="Puled Out-Error Details" v-model="check_type">
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
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(item, index) in check_tbl" :key="index">
                                                            <td>
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
                                    <div class="col-md-5">
                                        <br>
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <input type="text" :disabled="true" v-model="paying_habit"  v-validate="" name="paying habit"  class="form-field__input">
                                                <label class="form-field__label">Paying Habit (Past 3 Months)</label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('paying habit') }}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
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
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" v-model="cl"  v-validate="'required'" name="CL"  class="form-field__input">
                            <label class="form-field__label">CL</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('CL') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" v-model="pdc"  v-validate="'required'" name="PDC"  class="form-field__input">
                            <label class="form-field__label">PDC</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('PDC') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" :value="total" readonly="true" v-validate="'required'" name="Total"  class="form-field__input">
                            <label class="form-field__label">TOTAL</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('Total') }}</span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" v-model="ar"  v-validate="'required'" name="A/R"  class="form-field__input">
                            <label class="form-field__label">A/R</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('A/R') }}</span>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" v-model="order"  v-validate="'required'" name="Order"  class="form-field__input">
                            <label class="form-field__label">ORDER</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('Order') }}</span>
                    </div>
                </div>
                <div class="col-xs-7 col-sm-7 col-md-2">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" :value="excess2" readonly="true"  v-validate="'required'" name="excess2"  class="form-field__input">
                            <label class="form-field__label">EXCESS</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('excess2') }}</span>
                    </div>
                </div>
                <div class="col-xs-5 col-sm-5 col-md-2">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" :value="percent2" readonly="true" v-validate="'required'" name="percent2"  class="form-field__input">
                            <label class="form-field__label">% (ex: 100)</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('percent2') }}</span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <select :disabled="$parent.disabledinput" v-model="commitmentfollowup" name="commitmentfollowup" v-validate="'required'" class="form-field__input" 
                            >
                                <option :value="'No need follow-up'" >No need follow-up</option>
                                <option :value="'For follow-up'" >For follow-up</option>
                                <option :value="'Settled'" >Settled</option>
                                <option :value="'For CL increase'" >For CL increase</option>
                                <option :value="'For CL allocation'" >For CL allocation</option>
                            </select>
                            <label class="form-field__label">Commitment For Followup</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('commitmentfollowup') }}</span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                        <div class="form-field__control mdb-bgcolor">
                            <textarea :readonly="true" class="form-field__textarea"  cols="4" rows="4" v-validate="" v-model="additional_info" name="additional-info"></textarea>
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
                            <textarea :readonly="true" class="form-field__textarea"  cols="4" rows="4" v-validate="" v-model="remarks" name="remarks"></textarea>
                            <label class="form-field__label">Approver Remarks</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <h6><span class="errors">{{ errors.first('remarks') }}</span></h6>
                    </div>
                </div>
                <!-- status is rejected or approve -->
                <div>
                    <!-- Digital Signatures -->
                    <div class="col-md-3">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" :value="computedfullname" name="preparedby" readonly="true">
                                <label class="form-field__label">Prepared By</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-for="(val, index) in endorser" :key="index">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" :value="val || ''" name="endorsement" readonly="true">
                                <label class="form-field__label">
                                    {{index | endorsTerm}} Endorsement
                                </label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-show="selected.status > 1">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" :value="selected.approvedby || ''" name="approve" readonly="true">
                                <label class="form-field__label">Approved/Rejected By</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                </div>
            </form>


    </div>
</template>
<script>
// let leavetype = ['Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave', 'Others'];
// let status = ['Pending', 'Approved', 'Rejected'];
const countTerms = ['st', 'nd', 'rd', 'th'];

let excludeBody = [
            'compRows',
            'divRows',
            'branchRows',
            'check_fields',
            'overdue_fields',
            'reciever_emails',
            'isDisable',
            'search_customer',
            'customerList',
            'employeeList',
            'search_manager',
            'search_employee',
            'compFocus',
            'selectedComp',
            'loader',
            'loader2',
            'errBranch',
            'errMsg'
        ];

export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
        search_customer: '',
        search_manager: '',
        search_employee: '',

        customerList: [],
        employeeList: [],

        // customerData: {},
        loader: false,
        loader2: false,
        errBranch: false,
        isDisable: true,
        compFocus: false,
        

        errMsg: '',

        empID_: '',
        dateoverride: moment(new Date()).format('MM/DD/YYYY hh:mm A'),
        mode: '',
        commited_time: '',
        commited_date: moment(new Date()).format('MM/DD/YYYY'),
        po_so: '',
        // company
        selectedComp: '',
        company: '',
        // end
        division: '',
        branch: '',
        customer_name: '',
        sales_employee: '',
        sales_manager: '',
        amount_order: '',
        
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
        excess2: '',
        last_cl: '',
        commit_cl: '',
        paying_habit: '',
        check_type: [],
        commitmentfollowup: '',
        additional_info: '',
        remarks: '',
        
        overrideID:'',
        percent: '',
        percent2: '',
        
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
        status: 0,
        compRows: [],
        divRows: [],
        branchRows: [],
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        },
        
    },
    filters: {
        endorsTerm(val) {
            // return this.selected.endorsedby_
            return val > 2? (val+1) + countTerms[3]: (val+1) + countTerms[val]
            // return val == 0? 'Pending':
            //        val == 1 ? 'Endorsed':
            //        val == 2 ? 'Approved': 'Rejected'
        }
    },
    methods:{
        addOverride(){
           
        },
        updateOverride(){
            
        },
        async deleteOverride(){
            
        },
         // ACTIONS FOR LEAVE I.E APPROVE / REJECT / CANCEL
        async requestActionOverride(status = null){
            
        },
        handleBlur(e){
            e.preventDefault();
        },
        selectCompany(val){
            
            this.selectedComp =  val.name;
            this.company = val.name;
            this.compFocus = false;
            this.MDBINPUT();
            
            this.loader2 = true;
            this.errMsg = '';
            axios.get(SAP+'/login', {
                company: val.name,
                user: val.user,
                pwd: val.pwd
            })
            .then(({data})=>{
                this.isDisable = false;
                this.loader2 = false;
            }).catch(()=>this.errMsg = 'Network problem please contact your IT-Department');
        },
        selectedEmployee(val, tag = 'employee') {
            if(tag == 'manager') {
                this.search_manager = val;
                this.sales_manager = val;
            } else {
                this.search_employee = val;
                this.sales_employee = val;
            }
            
        },
        selectedCustomer(val) {
            
            this.search_customer = val.CardName;
            this.customer_name = val.CardName;
            this.isDisable = true;
            this.errBranch = false;
            
        },
        selectDateCommitment(val){
            this.commited_date = moment(val).format('MM/DD/YYYY');
        },
        selectDateInvoice(val){
            this.overdue_fields['invoice_date'] = moment(val).format('MM/DD/YYYY');
        },
        selectDateCheck(val){
            this.check_fields['check_date'] = moment(val).format('MM/DD/YYYY');
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
                if(key == 'loader' || key == 'errBranch' ||
                   key == 'compFocus' || key == 'loader2'){
                    this.$data[key] = false;
                }
                if(key == 'isDisable') {
                    this.$data[key] = true;
                }
                if(key == 'dateoverride' || key == 'commited_date'){
                    this.dateoverride = moment(new Date()).format('MM/DD/YYYY');
                    this.commited_date = moment(new Date()).format('MM/DD/YYYY');
                }
            });
            this.$data['check_type'] = [];
            this.$data['overdue_tbl'] = [];
            this.$data['overdue_fields'] = {
                invoice_num: '',
                invoice_date: moment(new Date()).format('MM/DD/YYYY'),
                amount: '',
                age: '',
                division: '',
            };
            this.$data['check_tbl'] = [];
            this.$data['check_fields'] = {
                bank: '',
                checkno: '',
                check_date: moment(new Date()).format('MM/DD/YYYY'),
                amount: '',
            };
            $("#myModal").modal("hide");
        },
        async setDataForEdit(data = null){
            for(let i in data)
            {
                this.$data[i] = await data[i];
                if(i == 'customer_name') {
                    this.$data['search_customer'] = await data[i];
                    // parse json data here for customerList
                }
                if(i == 'sales_employee') {
                    this.$data['search_employee'] = await data[i];
                    // parse json data here for customerList
                }
                if(i == 'sales_manager') {
                    this.$data['search_manager'] = await data[i];
                    // parse json data here for customerList
                }
                // if(i == 'customerData') {
                //     let jsonCustomer = await JSON.parse(data[i]);
                //     this.$data[i] = jsonCustomer;
                //     this.$data['customerList'] = [jsonCustomer];
                // }
                if(i == 'check_tbl' || i == 'overdue_tbl')
                    this.$data[i] = await JSON.parse(data[i]);
                if(i == 'check_type' && data[i]) 
                    this.$data[i] = await (data[i]).split(",");
                if(i == 'check_type' && !data[i])
                    this.$data[i] = [];
            }
            
            
            // console.log(data, this.$data);
            // return;
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
        },
        searchEmployee(){
            this.loader = false;
			let validSearch = /^[a-zA-Z0-9, Ññ)\._-]+$/g
			let regex = RegExp(validSearch);
            this.sales_employee = '';
            this.errMsg = '';

		},
        searchManager(){
            this.loader = false;
			let validSearch = /^[a-zA-Z0-9, Ññ)\._-]+$/g
			let regex = RegExp(validSearch);
            this.sales_manager = '';
            this.errMsg = '';

		},
        getCustomer(){
           
        }
    },
    computed:{
        isFormValid(){
            return !Object.keys(this.fields).some(key => this.fields[key].invalid) 
                    && !this.errBranch && this.sales_employee && this.sales_manager;
        },
        endorser(){
            return (this.selected.endorsedby_? this.selected.endorsedby_.split(',') : []);
        },
       
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
        computedposname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.posname  : this.userinfo.posname;
        },
        computedbranchname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.branchname  : this.userinfo.branchname;
        },
        computedStatus() {
            const val = this.status;
            let endorseCount = (this.selected.endorsedby_? this.selected.endorsedby_.split(',') : []).length;
            
            const lastdigit = endorseCount.toString().split('').pop();
            if(lastdigit == '1'){
                endorseCount = endorseCount+countTerms[endorseCount-1];
            } else if(lastdigit == '2') {
                endorseCount = endorseCount+countTerms[endorseCount-1];
            } else if(lastdigit == '3') {
                endorseCount = endorseCount+countTerms[endorseCount-1];
            } else if(lastdigit == '11') {
                endorseCount = endorseCount+countTerms[3];
            }else {
                endorseCount = endorseCount+countTerms[endorseCount-1];
            }
            return val == 0? 'Pending':
                   val == 1 ? endorseCount + ' Endorsement':
                   val == 2 ? 'Approved': 'Rejected'
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
       
    },
    beforeDestroy(){
        bus.$off('setupdate', this.test)
    },
    mounted(){

        this.MDBINPUT();
        if(this.true){
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