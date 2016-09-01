@extends('layouts.app')

@section('specificJS')
@stop

@section('content')
    <div style="background-color: #004468;" class="row nav top-nav">
        <div class="col-lg-3"><a href="/" class="left"><img src="/images/logo-transp.png" class="chh_logo"></a></div>
        <div class="col-lg-9">
            <ul class="nav navbar-nav right hidden-xs">
                <li> <a href="/">Home</a></li>
                <li> <a href="/about" style="padding-right: 40px !important;">About</a></li>
                <li class="visible-sm"><a href="https://github.com/alanbraz/cognitive-head-hunter" target="_blank"> <span class="fa fa-code-fork"> </span> Fork me</a></li>
            </ul>
            <ul class="nav navbar-nav nav-pills right visible-xs">
                <li> <a href="/"><span class="fa fa-home"></span></a></li>
                <li> <a href="/about"><span class="fa fa-info"></span></a></li>
                <li><a href="https://hub.jazz.net/git/alanbraz/job-hunters" target="_blank"><span class="fa fa-code-fork"></span></a></li>
            </ul>
        </div>
    </div>
    <div id="modal" style="padding:10px;margin-top:10px;margin-bottom:10px; min-height: 600px;" class="row modal-content">
        <h1>Cognitive Head Hunter Terms of Use </h1>
        <p>This Agreement sets forth the terms governing your use of the your-celebrity-app application (“the Application”). By using the Application, you agree to these Terms of Use; if you do not agree to these all of these Terms of Use, do not use this Application.</p>
        <p>The Application is made available to you, as an individual person, non-exclusively by International Business Machines Corporation (“IBM”) at no charge via an Internet connection provided by you.</p>
        <p>The Application and any related software, documentation, content or other materials provided by IBM in connection with the Application are owned by IBM or a third party supplier, and are copyrighted and licensed, not sold. Transfer of your rights under this Agreement is not permitted.</p>
        <p>You are authorized to use the Application and associated content for your own personal use for informational purposes only. The Application is not intended to be used for productive or commercial purposes, and such use is not permitted. The Application relies on Twitter® content. Twitter® content, which may include profile information associated with Twitter® IDs, is neither owned nor controlled by IBM or its suppliers, and IBM and its suppliers do not license or otherwise provide any rights in the content. Content may include materials that are illegal, inaccurate, misleading, indecent, or otherwise objectionable. IBM or its suppliers have no obligation to review, filter, verify, edit or remove any content. However, IBM or its suppliers may, at their sole discretion, do so. IBM is not responsible for any Twitter® content that may have been modified or removed on the Twitter® website. By providing IBM your personal Twitter® ID, you are consenting to IBM’s use of your Twitter information within the Application. You are prohibited from uploading the Twitter® ID of any other person or party.</p>
        <p>Your use of the Application is also subject to IBM’s Online Application Privacy Statement http://www.ibm.com/privacy/us/en/</p>
        <p>The Application may be unavailable during maintenance scheduled determined by IBM, and other scheduled or non-scheduled downtimes may occur. Either party may terminate use or access to the Application at any time.</p>
        <p>You are responsible for your use of the Application and associated content and the results obtained from their use. You are also responsible for any information, data or other materials (“User Materials”) that you submit or provide in connection with the Application, including ensuring that you have all necessary authorizations to permit IBM and its subcontractors to use, host, cache, record, copy and display such User Materials without charge. IBM has no responsibility for the User Materials that you submit or provide in connection with the Application. All User Materials which you submit or provide to the Application or to IBM are non-confidential.</p>
        <p>The Application source code is available for download under a separate URL that contains associated license terms.</p>
        <p>The Application and associated content are provided “AS IS”, with no warranties, express or implied, including without limitation, warranties of merchantability, fitness for a particular purpose, or non-infringement. IBM and its affiliates and suppliers will not be liable, under any contract, tort, strict liability, or other theory, for any direct, special, punitive, indirect, incidental, or consequential damages, including, but not limited to, loss of or damage to data, loss of anticipated revenue, profits, savings, or goodwill, work stoppage or impairment of other assets, whether or not foreseeable and whether or not you have been advised of the possibility of such damages. You are responsible for compliance with any applicable laws and regulations which may govern your accessing and use of the Application.</p>
        <p>It is IBM’s policy to respect the intellectual property rights of others. To report the infringement of copyrighted material, please visit the Digital Millennium Copyright Act Notices page at: http://www.ibm.com/legal/us/en/dmca.html.</p>
        <p>The laws of the State of New York, excluding its conflicts of laws rules, govern this Agreement and your use of the Application.</p>
        <p>IBM wants to know what you think about the Application and how IBM can make them better. You can do this by providing your comments and feedback via online forums or other submission vehicles which IBM makes available to you. By providing your comments and feedback to IBM, you authorize IBM to publish and display them and to use them in the development and enhancement of its products and Application.</p>
        <p>The foregoing is the complete agreement between you and IBM regarding your use of the Application and replaces any prior communications related to such use.</p>
    </div>
@stop
