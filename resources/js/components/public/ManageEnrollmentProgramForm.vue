<template>
    <div>

            <form method="post">
                <h3 class="text-center form-title"><span class="dblUnderlined">{{this.$route.name}}</span></h3>
                <div class="col-md-12 col-lg-12">
                    <h5 class="form-subtitle"><em>&nbsp;</em></h5>
                </div>
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="relative-pos">
                            <div class="form-field__control">
                                <input :disabled="$parent.disabledinput" type="text" :value="company" name="company"  class="form-field__input"
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
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="relative-pos">
                            <div class="form-field__control">
                                <input :disabled="disableCustomerField || $parent.disabledinput || isDisable || !company" type="text" v-model="search_customer" 
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
                            <input :disabled="$parent.disabledinput || isDisable" type="text" class="form-field__input" :value="dateenrolled" name="dateenrolled" readonly="true" >
                            <label class="form-field__label">Date & Time</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- for branch & div -->
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
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
                            <select :disabled="$parent.disabledinput" v-model="division" name="division" v-validate="'required'" class="form-field__input" 
                            >
                                <option v-for="div in divRows" :value="div.name" :key="div.id">{{div.name}}</option>
                            </select>
                            <label class="form-field__label">Division</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('division') }}</span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" v-model="program_code" name="program_code"  v-validate="'required'" class="form-field__input">
                            <label class="form-field__label">BDP Code</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('program_code') }}</span>
                    </div>
                </div>  
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" v-model="program_name" name="program_name"  v-validate="'required'" class="form-field__input">
                            <label class="form-field__label">BDP Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('program_name') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <select :disabled="$parent.disabledinput" v-model="teritory" name="teritory" v-validate="'required'" class="form-field__input" 
                            >
                                <option v-for="(div, index) in teritoryList" :value="div.teritoryName" :key="index">{{div.teritoryName}}</option>
                            </select>
                            <label class="form-field__label">Teritory</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('teritory') }}</span>
                    </div>
                </div>
                
                <div class="clearfix"></div>
                <hr style="border-top: 8px solid #1F7193">
                <!-- <div class="col-md-12 col-lg-12">
                    <h4 class="text-center form-title"><span class="dblUnderlined">ITEMS (optional)</span></h4>
                </div> -->
                <div class="col-md-12">
                    <div class="panel-group" id="override-accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading form-subtitle">
                                <h5 class="form-subtitle panel-title">
                                    <a data-toggle="collapse" data-parent="#override-accordion" :href="'#1'">
                                        <em>
                                            <span><i class="far fa-plus-square" style="direction: rtl; font-size: 19px;"></i></span>
                                            ITEMS (optional)
                                        </em>
                                    </a>
                                </h5>
                            </div>
                            <div :id="1" :class="'panel-collapse collapse'">
                                <div class="panel-body">
                                    <div class="col-md-12">
                                            <div class="mdb-table-overflow">
                            <table width="100%" class="table table-hover mdb-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ITEM NAME</th>
                                            <th class="text-center">ITEM CODE</th>
                                            <th class="text-center" style="min-width: 18px;">QTY</th>
                                            <th class="text-center" style="min-width: 20px;">UOM</th>
                                            <th class="text-center" colspan="2" style="width: 800px;">REMARKS</th>

                                        </tr>
                                        <tr v-if="!$parent.disabledinput">
                                            <!-- line # -->
                                            <!-- <td>
                                                 <div class="mdb-form-field form-group-limitx">
                                                    <div class="form-field__control form-field--is-filled">
                                                        <input type="text" :disabled="!company" name="lineNo" v-model="lineNo" class="form-field__input inline-input" >
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('lineNo') }}</span>
                                                </div>
                                            </td> -->
                                            
                                            <!-- item name -->
                                            <td colspan="1">
                                                <div class="mdb-form-field form-group-limitx">
                                                    <div class="mdb-form-field form-group-limitx">
                                                        <div class="relative-pos">
                                                            <div class="form-field__control form-field--is-filled">
                                                                <input :disabled="$parent.disabledinput || !company" type="text" v-model="search_itemName" name="itemName"  class="form-field__input inline-input"
                                                                @keyup.prevent="getItemName"
                                                                @focus="itemNameFocus = true"
                                                                @blur="handleBlur"
                                                                >
                                                                <div class="form-field__bar"></div>
                                                            </div>
                                                            <span class="errors">{{ errors.first('company') }}</span>
                                                            <!-- loader and err msg -->
                                                            <div v-if="loader3">
                                                                <i class="fas fa-spinner fa-spin"></i> 
                                                                <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                                                            </div>
                                                            <div class="fixed-pos bg-white suggestion_filter" 
                                                                v-if="itemNameFocus">
                                                                <ul>
                                                                    <li v-for="comp in itemNameRows" 
                                                                    @click.prevent="selectedItemName(comp)"
                                                                    :key="comp.ItemCode">
                                                                        {{comp.ItemName}}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <!-- item code -->
                                            <td>
                                               <div class="mdb-form-field form-group-limitx">
                                                    <div class="form-field__control form-field--is-filled">
                                                        <input type="text" :readonly="true" name="itemCode" :value="itemCode" class="form-field__input inline-input" >
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('itemCode') }}</span>
                                                </div>
                                            </td>
                                            <!-- qty -->
                                            <td>
                                                <div class="mdb-form-field form-group-limitx">
                                                    <div class="form-field__control form-field--is-filled">
                                                        <input type="text" :disabled="!company" name="qty" v-model="qty" class="form-field__input inline-input" >
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('qty') }}</span>
                                                </div>
                                            </td>
                                            <!-- oum -->
                                            <td>
                                                <div class="mdb-form-field form-group-limitx">
                                                    <div class="form-field__control form-field--is-filled">
                                                        <input type="text" :disabled="!company" name="oum" v-model="uom" class="form-field__input inline-input" >
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('uom') }}</span>
                                                </div>
                                            </td>
                                            <!-- remarks -->
                                            <td>
                                                
                                                <div class="mdb-form-field">
                                                    <div class="form-field__control">
                                                        <input type="text" :disabled="!company" class="form-field__input inline-input" v-model="itemRemarks" v-validate="" name="itemRemarks" @keydown.enter.prevent="appendTable">
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('itemRemarks') }}</span>
                                                </div>
                                            </td>
                                            <td style="width: 10px;">
                                                <button class="btn btn-primary" @click.prevent="appendTable">add</button>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in entries" :key="index">
                                            
                                            <!-- <td>
                                                {{item.lineNo}}
                                            </td> -->
                                            <td>
                                                <span style="padding: 0 12px;">
                                                    <a v-if="!$parent.disabledinput" @click="removeRow(index)"><i class="fas fa-trash text-danger"></i></a>
                                                </span>
                                                <span style="padding-right: 8px;">{{index + 1}} </span>
                                                {{item.itemName}}
                                            </td>
                                            <td>
                                                {{item.itemCode}}
                                            </td>
                                            <td>
                                                {{item.qty}}
                                            </td>
                                            <td>
                                                {{item.uom}}
                                            </td>
                                            <td colspan="2">
                                                {{item.remarks}}
                                            </td>
                                        </tr>

                                    </tbody>
                            </table>

                    </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-12 col-lg-12">
                    <h4 class="form-subtitle text-center form-title"><em><span class="dblUnderlinedx">ITEMS (optional)</span></em></h4>
                </div> -->
                <div class="col-md-12">
                    <!-- <div class="mdb-table-overflow"> -->
                            <!-- <table width="100%" class="table table-hover mdb-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ITEM NAME</th>
                                            <th class="text-center">ITEM CODE</th>
                                            <th class="text-center" style="min-width: 18px;">QTY</th>
                                            <th class="text-center" style="min-width: 20px;">UOM</th>
                                            <th class="text-center" colspan="2" style="width: 800px;">REMARKS</th>

                                        </tr>
                                        <tr v-if="!$parent.disabledinput">
                                            <td colspan="1">
                                                <div class="mdb-form-field form-group-limitx">
                                                    <div class="mdb-form-field form-group-limitx">
                                                        <div class="relative-pos">
                                                            <div class="form-field__control form-field--is-filled">
                                                                <input :disabled="$parent.disabledinput || !company" type="text" v-model="search_itemName" name="itemName"  class="form-field__input inline-input"
                                                                @keyup.prevent="getItemName"
                                                                @focus="itemNameFocus = true"
                                                                @blur="handleBlur"
                                                                >
                                                                <div class="form-field__bar"></div>
                                                            </div>
                                                            <span class="errors">{{ errors.first('company') }}</span>
                                                            
                                                            <div v-if="loader3">
                                                                <i class="fas fa-spinner fa-spin"></i> 
                                                                <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                                                            </div>
                                                            <div class="fixed-pos bg-white suggestion_filter" 
                                                                v-if="itemNameFocus">
                                                                <ul>
                                                                    <li v-for="comp in itemNameRows" 
                                                                    @click.prevent="selectedItemName(comp)"
                                                                    :key="comp.ItemCode">
                                                                        {{comp.ItemName}}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td>
                                               <div class="mdb-form-field form-group-limitx">
                                                    <div class="form-field__control form-field--is-filled">
                                                        <input type="text" :readonly="true" name="itemCode" :value="itemCode" class="form-field__input inline-input" >
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('itemCode') }}</span>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="mdb-form-field form-group-limitx">
                                                    <div class="form-field__control form-field--is-filled">
                                                        <input type="text" :disabled="!company" name="qty" v-model="qty" class="form-field__input inline-input" >
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('qty') }}</span>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="mdb-form-field form-group-limitx">
                                                    <div class="form-field__control form-field--is-filled">
                                                        <input type="text" :disabled="!company" name="oum" v-model="uom" class="form-field__input inline-input" >
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('uom') }}</span>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                
                                                <div class="mdb-form-field">
                                                    <div class="form-field__control">
                                                        <input type="text" :disabled="!company" class="form-field__input inline-input" v-model="itemRemarks" v-validate="" name="itemRemarks" @keydown.enter.prevent="appendTable">
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('itemRemarks') }}</span>
                                                </div>
                                            </td>
                                            <td style="width: 10px;">
                                                <button class="btn btn-primary" @click.prevent="appendTable">add</button>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in entries" :key="index">
                                            
                                            <td>
                                                <span style="padding: 0 12px;">
                                                    <a v-if="!$parent.disabledinput" @click="removeRow(index)"><i class="fas fa-trash text-danger"></i></a>
                                                </span>
                                                <span style="padding-right: 8px;">{{index + 1}} </span>
                                                {{item.itemName}}
                                            </td>
                                            <td>
                                                {{item.itemCode}}
                                            </td>
                                            <td>
                                                {{item.qty}}
                                            </td>
                                            <td>
                                                {{item.uom}}
                                            </td>
                                            <td colspan="2">
                                                {{item.remarks}}
                                            </td>
                                        </tr>

                                    </tbody>
                            </table> -->
                    <!-- </div> -->
                </div>
                <div class="clearfix"></div>

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
                <div class="clearfix"></div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <!-- <img id="work-attachments" :src="'public/images/priemer_jacket.jpg'" alt="Avatar"> -->
                    <label for="prog__attachment1">
                        <span class="profile-cam-icon" style="position: relative;"><i class="fas fa-camera"></i></span>
                         <span style="position: relative; left: 10px; bottom: 3px;">
                             <span v-show="!progmechanic_attachment" id="file-label">Program Mechanics</span>
                             <a id="file-label1" :href="'storage/app/'+progmechanic_attachment" target="_blank" v-show="progmechanic_attachment">attachment - {{'Program Mechanics'}}</a>
                        </span>
                    </label>
                    <input id="prog__attachment1" class="program-attachment" type="file" @change="attachFile" style="display:none;">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <!-- <img id="work-attachments" :src="'public/images/priemer_jacket.jpg'" alt="Avatar"> -->
                    <label for="prog__attachment2" >
                        <span class="profile-cam-icon" style="position: relative;"><i class="fas fa-camera"></i></span>
                         <span style="position: relative; left: 10px; bottom: 3px;">
                             <span v-show="!progagree_attachment" id="file-label">Program Agreement</span>
                             <a id="file-label2" :href="'storage/app/'+progagree_attachment" target="_blank" v-show="progagree_attachment">attachment - {{'Program Agreement'}}</a>
                        </span>
                    </label>
                    <input id="prog__attachment2" class="program-attachment" type="file" @change="attachFile" style="display:none;">
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                        <div class="form-field__control mdb-bgcolor">
                            <textarea :disabled="$parent.$data.forapprover != 'approval'" class="form-field__textarea"  cols="4" rows="4" v-validate="" v-model="remarks" name="remarks"></textarea>
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
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="computedfullname" name="preparedby" readonly="true">
                                <label class="form-field__label">Prepared By</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-for="(val, index) in endorser" :key="index">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="val || ''" name="endorsement" readonly="true">
                                <label class="form-field__label">
                                    {{index | endorsTerm}} Endorsement
                                </label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-show="selected.status > 0">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="selected.approvedby || ''" name="approve" readonly="true">
                                <label class="form-field__label">Approved/Rejected By</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
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
                    <!-- disabledIfNoApprover || isDisable || !isFormValid || !isRequiredFieldsValid" -->
                    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addEnrollmentProgram" :disabled="isDisable || !isFormValid || !isRequiredFieldsValid || disabledIfNoApprover"  v-if="!enrollmentID && $parent.$data.forapprover != 'approval'">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateEnrollmentProgram" :disabled="isDisable || !isFormValid || !isRequiredFieldsValid || disabledIfNoApprover" v-if="enrollmentID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput ">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteenrollmentprog" :disabled="isDisable" v-if="enrollmentID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput ">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionEnrollmentProgram(1)" v-if="enrollmentID && $parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionEnrollmentProgram(2)" v-if="enrollmentID && $parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel">
                    <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionEnrollmentProgram(0)" v-if="enrollmentID && $parent.$data.forapprover == 'approval' && selected.status >= 1">
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
            'reciever_emails',
            'isDisable',
            'search_customer',
            'customerList',
            'employeeList',
            'search_employee',
            'search_itemName',
            'compFocus',
            'selectedComp',
            'loader',
            'loader2',
            'loader3',
            'errBranch',
            'errMsg',
            'headers',
            'disableCustomerField',

            'qty',
            'itemCode',
            'itemNameFocus',
            'itemName',
            'itemNameRows',
            'itemRemarks',
            'lineNo',
            'uom',
            'progmechanic_attachment',
            'progagree_attachment',
            'teritoryList',
            'oldDataprogmechanic_attachment',
            'oldDataprogagree_attachment',
        ];

export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
        oldDataprogmechanic_attachment:'',
        oldDataprogagree_attachment: '',
        
        enrollmentID:'',
        search_customer: '',
        search_employee: '',

        customerList: [],
        employeeList: [],
        teritoryList: [
            { teritoryName: 'South Luzon'},
            { teritoryName: 'North Luzon'},
            { teritoryName: 'GMA'},
            { teritoryName: 'Commercial'},
            { teritoryName: 'Visayas'},
            { teritoryName: 'North Mindanao'},
            { teritoryName: 'South Mindanao'},
            { teritoryName: 'Office Account'},
        ],
        teritory: '',
        progmechanic_attachment: '',
        progagree_attachment: '',

        // customerData: {},
        loader: false,
        loader2: false,
        errBranch: false,
        isDisable: false,
        compFocus: false,
        disableCustomerField: true,
        

        errMsg: '',

        empID_: '',
        dateenrolled: moment(new Date()).format('MM/DD/YYYY hh:mm A'),

        // company
        compRows: [],
        selectedComp: '',
        company: '',
        customer_name: '',
        
        // end
        branch: '',
        division: '',
        program_code: '',
        program_name: '',    

        additional_info: '',
        
        approvedby: '',

        // tables
        loader3: false,
        itemNameFocus: false,
        // lineNo: '',
        itemCode: '',
        qty: '',
        uom: '',
        search_itemName: '',
        itemRemarks: '',
        itemName:'',
        itemNameRows:[],
        // table items contents
        entries: [],
        

       
        status: 0,
        divRows: [],
        branchRows: [],
        remarks: '',
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        },
        
    },
    filters: {
        filter_attachment(val){
            if(val && typeof val == 'string'){
                return  (val.split("/")[3]);
            }

            return val;
        },
        endorsTerm(val) {
            // return this.selected.endorsedby_
            // return val > 2? (val+1) + countTerms[3]: (val+1) + countTerms[val]
            // return val == 0? 'Pending':
            //        val == 1 ? 'Endorsed':
            //        val == 2 ? 'Approved': 'Rejected'
        }
    },
    methods:{
        attachFile(e){
            
            //  input.program-attachment[type=file]
            // input.program-attachment[type=file]
            
            if(e.target.value != ''){
			    let file    = e.target.files[0]; //sames as here

			    let type = file['type'];
                
			    const validImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'application/pdf'];
			    if (!validImageTypes.includes(type)) {
				      alert('Invalid file type');
				      return false;
				}
				if(file.size >= 4000000)
				{
					alert('Filesize exceed 4MB');
				    return false;
				}

				if (file) {
                    // document.querySelector(elementLabel).innerHTML = file.name;
                    // document.querySelector(elementLabel).innerHTML = file.name;
                    
				    let reader  = new FileReader();
                    let preview = '';
                    if(e.target.id == 'prog__attachment1'){
                        preview = document.querySelector('#file-label1'); //selects the query named img
                        this.progmechanic_attachment = file;
                    }
                    else{
                        preview = document.querySelector('#file-label2'); //selects the query named img
                        this.progagree_attachment = file;
                        console.log(preview);
                    }


                    reader.onloadend = function () {
				          preview.href = reader.result;
				     }
				     reader.readAsDataURL(file);
                    
				    //  return file;
                    
                    // if(e.target.id == 'prog__attachment1'){
                    //     this.progmechanic_attachment = file;
                    // }
                    // else{
                    //     this.progagree_attachment = file;
                    // }
				}
			}else{

				return null;
			}
        },
        async addEnrollmentProgram(){
            // console.log( 
            //     'mechanic', typeof this.progmechanic_attachment,
            //     'progagree', typeof this.progagree_attachment);
            //     return;
            if(this.isFormValid){
                
                this.isDisable = true;
                
                
                // if(file)
                const formData = new FormData();
                
                if(this.progagree_attachment){
                    await formData.append('attachment[]', this.progagree_attachment || '');
                    await formData.append('attachtype[]', 'progagree_attachment');
                }
                
                
                if(this.progmechanic_attachment){
                    await formData.append('attachment[]', this.progmechanic_attachment || '');
                    await formData.append('attachtype[]', 'progmechanic_attachment');
                }
                
                
                for (const key in this.$data) {
                    if (!excludeBody.includes(key)) {
                        if(key == 'entries'){
                            formData.append(key, JSON.stringify(this.$data[key]));
                        }else {
                            formData.append(key, this.$data[key]);
                        }
                        
                    }
                }

                formData.append('reciever_emails[]', this.$parent.reciever_emails);
                axios.post('api/addenrollmentprog', formData).then(({data})=>{
                    this.$parent.addRow(data);
                    $("#myModal").modal("hide");
                }).catch((err)=>{console.log(err);});
            }
        },
        async updateEnrollmentProgram(){
            if(this.isFormValid){
                
                this.isDisable = true;
                const oldDataprogmechanic_attachment = this.oldDataprogmechanic_attachment;
                const oldDataprogagree_attachment = this.oldDataprogagree_attachment;

                const formData = new FormData();
                // console.log( 
                // 'mechanic', typeof this.progmechanic_attachment,
                // 'progagree', typeof this.progagree_attachment);
                // return;
                
                if(typeof this.progagree_attachment == 'object'){
                    await formData.append('attachment[]', this.progagree_attachment || '');
                    await formData.append('attachtype[]', 'progagree_attachment');
                    await formData.append('attachmentOldPath[]', oldDataprogagree_attachment);
                }else{
                    await formData.append('progagree_attachment', oldDataprogagree_attachment);   
                }
                
                if(typeof this.progmechanic_attachment == 'object'){
                    await formData.append('attachment[]', this.progmechanic_attachment || '');
                    await formData.append('attachtype[]', 'progmechanic_attachment');
                    await formData.append('attachmentOldPath[]', oldDataprogmechanic_attachment);
                }else{
                    await formData.append('progmechanic_attachment', oldDataprogmechanic_attachment);
                }
                
                for (const key in this.$data) {
                    if (!excludeBody.includes(key)) {
                        if(key == 'entries'){
                            formData.append(key, JSON.stringify(this.$data[key]));
                        }else {
                            formData.append(key, this.$data[key]);
                        }
                        
                    }
                }
                axios.post('api/updateenrollmentprog', formData).then(({data})=>{
                    this.$parent.updateRow(data);
                    $("#myModal").modal("hide");
                }).catch((err)=>{console.log(err);});
            }
        },
        async deleteenrollmentprog(){
            const reciever_emails = await this.$parent.reciever_emails;
            axios.post('api/deleteenrollmentprog/'+this.enrollmentID, {reciever_emails})
            .then(({data})=>{
                this.$parent.deleteRow(this.enrollmentID);
                $("#myModal").modal("hide");
            }).catch((err)=>{console.log(err);});
        },
         // ACTIONS FOR LEAVE I.E APPROVE / REJECT / CANCEL
        async requestActionEnrollmentProgram(status = null){
            let params =  this.$data;
           
          
            //  APPROVE
            if(status >= 1) {
                params.approvedby = (this.userinfo.fullname || '');
            }

            params['status'] = status;
            
            axios.post('api/actionenrollmentprog', params).then((response)=>{
                this.$parent.updateRow(response.data);
                $("#myModal").modal("hide");
            }).catch((err)=>{ console.log(err); });
        },
        handleBlur(e){
            e.preventDefault();
        },
        selectCompany(val){
            this.closeModal(false);
            this.selectedComp =  val.name;
            this.company = val.name;
            this.compFocus = false;
            this.disableCustomerField = true;
            this.customerList = [];
            this.MDBINPUT();
            
            
            this.loader2 = true;
            this.errMsg = '';
            
            axios.post('api/override-login', {
                id: val.id,
                type: 'company',
            })
            .then(({status})=>{
                if(status == 200) {
                    this.isDisable = false;
                    this.loader2 = false;
                    this.disableCustomerField = false;
                }else{
                    this.errMsg = 'An error occured when connecting please contact your IT-Department';    
                }
                
            }).catch((e)=>{
                console.log(e);
                this.errMsg = 'Unable to connect to SAP-DB please contact your IT-Department';
                this.isDisable = true;
            });
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
            axios.post(`api/consume-api`,{
                path: `BusinessPartnerGroups(${val.GroupCode})`,
                method: 'GET'
            })
            .then(async ({data})=>{
                // this.customerData = val
                if(data.status == 200) {
                    this.branch = await data.data.Name; 
                    this.isDisable = false;
                    this.MDBINPUT();
                }else{
                    this.errBranch = true;    
                }
                
                // console.log(data);
            }).catch(er=>{
                this.errBranch = true;
            });
        },
        selectedItemName(val) {
            
            this.search_itemName = val.ItemName;
            this.itemName = val.ItemName;
            // this.errItemName = false;
            this.itemNameFocus = false;
            this.itemCode = val.ItemCode;
        },
       
        appendTable(){
            if(this.itemName == '' || this.qty == '' || this.uom == '' || this.itemRemarks == ''){
                alert('Add values for this row');
                return;
            }
            

            this.entries.push({
                // lineNo: this.lineNo,
                itemCode: this.itemCode,
                itemName: this.itemName,
                qty: this.qty,
                uom: this.uom,
                remarks: this.itemRemarks
            });

            // this.lineNo = '';
            this.itemCode = '';
            this.itemName = '';
            this.search_itemName = '';
            this.qty = '';
            this.uom = '';
            this.itemRemarks = '';
        },
       
        removeRow(index)
        {
            this.entries.splice(index, 1);
        },
        
        closeModal(hideModal = true){

            if(!hideModal){
                this.search_customer = '';
                this.customer_name = '';
                this.branch = '';
                return;
            }

            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{

                if(key != 'compRows' && key != 'divRows' && key != 'branchRows'
                 && key != 'isDisable' && key != 'teritoryList'
                ) {
                    // this will be use for update button not be replaced by submit
                    //  when text field is cleared
                    
                        this.$data[key] =  '';
                    
                }
                if(key == 'loader' || key == 'errBranch' ||
                   key == 'compFocus' || key == 'loader2' || 
                   key == 'loader3' || key == 'isDisable'){
                    this.$data[key] = false;
                }
                
                if(key == 'dateenrolled'){
                    this.dateenrolled = moment(new Date()).format('MM/DD/YYYY hh:mm A');
                }
                if(key == 'entries'){
                    this.entries = [];
                }
            });
            
            this.$data['disableCustomerField'] = true;
            // $("#myModal").modal("hide");
        },
        async setDataForEdit(data = null){
            for(let i in data)
            {
                if(i != 'isDisable')
                this.$data[i] = await data[i];
                if(i == 'customer_name') {
                    this.$data['search_customer'] = await data[i];
                    // parse json data here for customerList
                }
                if(i == 'entries') {
                    this.$data['entries'] = await JSON.parse(data[i]);
                    // parse json data here for customerList
                }
                if(i == 'progagree_attachment')
                this.$data['oldDataprogagree_attachment'] = await data[i];

                if(i == 'progmechanic_attachment')
                this.$data['oldDataprogmechanic_attachment'] = await data[i];


                if(i == 'progagree_attachment' && typeof data[i] == 'object')
                this.$data[i] = '';

                if(i == 'progmechanic_attachment' && typeof data[i] == 'object')
                this.$data[i] = '';
                
                
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
            
			if(regex.test((this.search_employee))){
				
                // FOR UPDATE GET ONLY THE LAST NAME
                let search = '';
                if(this.selected.enrollmentID) {
                    search = (this.search_employee).split(", ");
                    search = search[0];
                    // return;
                }else{
                    search = this.search_employee;
                }
                
                
				axios.post('api/search-override-emp',{keyword: search}).then(res=>{
					if(res.data.length > 0 && res.status == 200){
						this.employeeList = res.data;
					}
                    this.loader = false;
				})
				.catch(err => this.errMsg = 'Network problem please contact your IT-Department');
			}

		},
        // searchManager(){
        //     this.loader = false;
		// 	let validSearch = /^[a-zA-Z0-9, Ññ)\._-]+$/g
		// 	let regex = RegExp(validSearch);
        //     this.sales_manager = '';
        //     this.errMsg = '';
		// 	if(regex.test((this.search_manager))){
				
        //         let search = '';
        //         if(this.selected.enrollmentID) {
        //             search = (this.search_manager).split(", ");
        //             search = search[0];
        //             // return;
        //         }else{
        //             search = this.search_manager;
        //         }
                
		// 		axios.post('api/search-override-emp',{keyword: search}).then(res=>{
		// 			if(res.data.length > 0 && res.status == 200){
		// 				this.employeeList = res.data;
		// 			}
        //             this.loader = false;
		// 		})
		// 		.catch(err => this.errMsg = 'Network problem please contact your IT-Department');
		// 	}

		// },
        getCustomer(){
            this.customer_name = '';
            this.errMsg = '';
            this.loader = true;
            const searchStr = ((this.search_customer).trim()).toUpperCase();
            axios.post(`api/consume-api`, {
                query:'BusinessPartners?$select=CardCode,EmailAddress,GroupCode,CardName,CardType',
                params: `&$filter=(startswith(CardName,\'${searchStr}\')) or startswith(CardCode, \'${searchStr}\')`,
                order: '&$orderby=CardName&$top=1000',
                method: 'GET'
            })
            .then(({data})=>{
                if(data.status == 200) {
                    this.loader = false;
                    this.customerList = data.data.value;     
                }else{
                    this.errMsg = 'Invalid data please try again';
                }
                
            }).catch(()=>{
                this.errMsg = 'Network problem please contact your IT-Department';
            });
        },
        getItemName(){
            this.itemName = '';
            this.errMsg = '';
            this.loader3 = true;
            // Items?$select=ItemCode,ItemName&$filter=startswith(ItemName, 'A')&$orderby=ItemCode&$top=1000
            const searchStr = ((this.search_itemName).trim()).toUpperCase();
            axios.post(`api/consume-api`, {
                query:'Items?$select=ItemCode,ItemName',
                params: `&$filter=(startswith(ItemName,\'${searchStr}\'))`,
                order: '&$orderby=ItemCode&$top=1000',
                method: 'GET'
            })
            .then(({data})=>{
                if(data.status == 200) {
                    this.loader3 = false;
                    this.itemNameRows = data.data.value;     
                }else{
                    this.errMsg = 'Invalid data please try again';
                }
                
            }).catch(()=>{
                this.errMsg = 'Network problem please contact your IT-Department';
            });
        }
    },
    computed:{
        disabledIfNoApprover(){
            return this.$parent.$data.forapprover != 'approval' && this.$parent.approvers && this.$parent.approvers.length < 1;
            
        },
       
        isFormValid(){
            // return true;
            return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        isRequiredFieldsValid(){
            return this.customer_name != '' && (this.progagree_attachment != '' && this.progmechanic_attachment != '');
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
            return val == 0 ? 'Pending':
                   val == 1 ? 'Approved': 'Rejected'
        }
    },

    created(){
        axios.get('api/get-override-company')
        .then(({data})=>{
            data.forEach( val => {
                if(val.type == 'company') {
                    this.compRows = (val.json);
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