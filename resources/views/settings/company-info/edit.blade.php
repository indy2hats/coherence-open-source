@extends('layout.main')

@section('content')
<!-- <div class="row">
    <div class="col-md-8">
        <strong>
            <h2 class="page-title"> Company Information </h3>
        </strong>
    </div>
</div> -->
<div class="row">
    <div class="col-lg-12">
        <div class="ibox tabs-container">
            <div class="ibox-title">
                <h5>Edit Company Information</h5>
               
            </div>
            <div class="ibox-content">
                <form action="{{route('company-info.store')}}" id="edit_company_info" method="POST" autocomplete="off"  enctype="multipart/form-data">
                    @csrf
                    <div class="form-group  row">
                        <label class="col-sm-2 col-form-label"> Logo</label>
                        <div class="col-sm-1 client-avatar">
                            <img alt="Logo" src="@if($info['company_logo']['value']){{ asset('storage/'.$info['company_logo']['value']) }}@else{{ asset('images/default-logo.png') }}@endif">  
                        </div>    
                        <div class="col-sm-9">
                            <input type="file" class="form-control" name='company_logo'>
                        </div>
                    </div>
                    <div class="form-group  row"><label class="col-sm-2 col-form-label"> Name <span class="required-label">*</span> </label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='company_name' value="{{$info['company_name']['value']}}">
                            <div class="text-danger text-left field-error" id="label_company_name"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"> Address Line 1<span class="required-label">*</span> </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="company_address_line1" value="{{$info['company_address_line1']['value']}}" />
                            <div class="text-danger text-left field-error" id="label_company_address_line1"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"> Address Line 2 </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="company_address_line2" value="{{$info['company_address_line2']['value']}}" />
                            <div class="text-danger text-left field-error" id="label_company_address_line2"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"> City <span class="required-label">*</span> </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="company_city" value="{{$info['company_city']['value']}}" />
                            <div class="text-danger text-left field-error" id="label_company_city"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"> State <span class="required-label">*</span> </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="company_state" value="{{$info['company_state']['value']}}" />
                            <div class="text-danger text-left field-error" id="label_company_state"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"> Country <span class="required-label">*</span> </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="company_country" value="{{$info['company_country']['value']}}" />
                            <div class="text-danger text-left field-error" id="label_company_country"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"> Zip/ Postal Code <span class="required-label">*</span> </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="company_zip" value="{{$info['company_zip']['value']}}" />
                            <div class="text-danger text-left field-error" id="label_company_zip"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"> Phone Number <span class="required-label">*</span> </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="company_phone" value="{{$info['company_phone']['value']}}" />
                            <div class="text-danger text-left field-error" id="label_company_phone"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"> Email ID <span class="required-label">*</span> </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="company_email" value="{{$info['company_email']['value']}}" />
                            <div class="text-danger text-left field-error" id="label_company_email"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"> Website Url <span class="required-label">*</span> </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="company_website_url" value="{{$info['company_website_url']['value']}}" />
                            <div class="text-danger text-left field-error" id="label_company_website_url"></div>
                        </div>
                    </div>
                    <div class="form-group  row"><label class="col-sm-2 col-form-label" > CIN <span class="required-label">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='company_cin' value="{{$info['company_cin']['value']}}">
                        <div class="text-danger text-left field-error" id="label_company_cin"></div>
                    </div>
                    </div>
                    <div class="form-group  row"><label class="col-sm-2 col-form-label" > VAT/ GSTIN ID <span class="required-label">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='company_gstin' value="{{$info['company_gstin']['value']}}">
                        <div class="text-danger text-left field-error" id="label_company_gstin"></div>
                    </div>
                    </div>
                    <div class="form-group  row"><label class="col-sm-2 col-form-label" > Financial Year <span class="required-label">*</span> </label>
                    <div class="col-sm-10">
                        <div class="custom-field-wrap">
                            <input type="text" class="form-control" name='company_financial_year_from' id='company_financial_year_from' value="{{isset($info['company_financial_year_from']) ? $info['company_financial_year_from']['value'] : ''}}" placeholder="financial year start day/month">
                            <input type="text" class="form-control" name='company_financial_year_to' id='company_financial_year_to' value="{{isset($info['company_financial_year_to']) ? $info['company_financial_year_to']['value'] : ''}}" placeholder="financial year end day/month">
                        </div>
                        <div class="text-danger text-left field-error" id="label_company_financial_year_from"></div>
                        <div class="text-danger text-left field-error" id="label_company_financial_year_to"></div>
                    </div>
                    </div>
                    <div class="form-group row"><label class="col-sm-2 col-form-label">Bank Account Details <span class="required-label">*</span> </label>
                        <div class="col-sm-10">
                            <textarea rows="8" class="form-control summernote"  name="company_bankaccount_details">{{$info['company_bankaccount_details']['value']}}</textarea>
                            <div class="text-danger text-left field-error" id="label_company_bankaccount_details"></div>
                        </div>
                    </div>
                    
                    <h4 class="m-t-none m-b">Edit Social Media Links</h4>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group  row"><label class="col-sm-2 col-form-label"> LinkedIn</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='company_linkedin_link' value="{{$info['company_linkedin_link']['value']}}">
                            <div class="text-danger text-left field-error" id="label_company_linkedin_link"></div>
                        </div>
                    </div>
                    <div class="form-group  row"><label class="col-sm-2 col-form-label"> Facebook </label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='company_facebook_link' value="{{$info['company_facebook_link']['value']}}">
                            <div class="text-danger text-left field-error" id="label_company_facebook_link"></div>
                        </div>
                    </div>
                    <div class="form-group  row"><label class="col-sm-2 col-form-label"> Instagram </label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='company_instagram_link' value="{{$info['company_instagram_link']['value']}}">
                            <div class="text-danger text-left field-error" id="label_company_instagram_link"></div>
                        </div>
                    </div>
                    <div class="form-group  row"><label class="col-sm-2 col-form-label"> Twitter </label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='company_twitter_link' value="{{$info['company_twitter_link']['value']}}">
                            <div class="text-danger text-left field-error" id="label_company_twitter_link"></div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/settings/company-info/script-min.js') }}"></script>
@endsection