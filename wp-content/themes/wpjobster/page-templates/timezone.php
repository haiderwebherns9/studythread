<div class="time_zone">
          <select id="time_zone" class="js-example-basic-single" name="timezone">
             <optgroup label="US/Canada">
			 <option value="US/Hawaii">Hawaii Time<?php  date_default_timezone_set("US/Hawaii"); echo "     ".date('h:i');?></option>
              <option value="US/Alaska">Alaska Time <?php date_default_timezone_set("US/Alaska"); echo "   ".date('h:i');?></option>
              <option value="US/Pacific">Pacific Time - US &amp; Canada<?php date_default_timezone_set("US/Pacific"); echo "   ".date('h:i');?></option>
              <option value="US/Arizona">Arizona Time<?php date_default_timezone_set("US/Alaska"); echo "   ".date('h:i');?></option>
              <option value="US/Mountain">Mountain Time - US &amp; Canada<?php date_default_timezone_set("US/Mountain"); echo "   ".date('h:i');?></option>
              <option value="US/Central">Central Time - US &amp; Canada<?php date_default_timezone_set("US/Central"); echo "   ".date('h:i');?></option>
              <option value="US/Eastern">Eastern Time - US &amp; Canada<?php date_default_timezone_set("US/Eastern"); echo "   ".date('h:i');?></option>
              <option value="Canada/Atlantic">Atlantic Time<?php date_default_timezone_set("Canada/Atlantic"); echo "   ".date('h:i');?></option>
              <option value="Canada/Newfoundland">Newfoundland Time<?php date_default_timezone_set("Canada/Newfoundland"); echo "   ".date('h:i');?></option>
		  </optgroup>
		  <optgroup label="America">
              <option value="America/Adak">America/Adak<?php date_default_timezone_set("America/Adak"); echo "   ".date('h:i');?></option>
              <option value="America/Santa_Isabel">America/Santa Isabel<?php date_default_timezone_set("America/Santa_Isabel"); echo "   ".date('h:i');?></option>
              <option value="America/Regina">Saskatchewan, Guatemala, Costa Rica Time<?php date_default_timezone_set("America/Regina"); echo "   ".date('h:i');?></option>
              <option value="America/Mazatlan">America/Mazatlan<?php date_default_timezone_set("America/Mazatlan"); echo "   ".date('h:i');?></option>
              <option value="America/Bogota">Bogota, Jamaica, Lima Time<?php date_default_timezone_set("America/Bogota"); echo "   ".date('h:i');?></option>
              <option value="America/Mexico_City">Mexico City Time<?php date_default_timezone_set("America/Mexico_City"); echo "   ".date('h:i');?></option>
              <option value="America/Asuncion">Asuncion Time<?php date_default_timezone_set("America/Asuncion"); echo "   ".date('h:i');?></option>
              <option value="America/Campo_Grande">America/Campo Grande<?php date_default_timezone_set("America/Campo_Grande"); echo "   ".date('h:i');?></option>
             <option value="America/Caracas">Caracas Time<?php date_default_timezone_set("America/Caracas"); echo "   ".date('h:i');?></option>
              <option value="America/Havana">America/Havana<?php date_default_timezone_set("America/Havana"); echo "   ".date('h:i');?></option>
              <option value="America/Halifax">Atlantic Standard Time<?php date_default_timezone_set("America/Halifax"); echo "   ".date('h:i');?></option>
              <option value="America/Buenos_Aires">Buenos Aires Time<?php date_default_timezone_set("America/Buenos_Aires"); echo "   ".date('h:i');?></option>
              <option value="Canada/Atlantic">Atlantic Time<?php date_default_timezone_set("Canada/Atlantic"); echo "   ".date('h:i');?></option>
              <option value="America/Montevideo">Montevideo Time<?php date_default_timezone_set("America/Montevideo"); echo "   ".date('h:i');?></option>
              <option value="America/Santiago">Santiago Time<?php date_default_timezone_set("America/Santiago"); echo "   ".date('h:i');?></option>
              <option value="America/Sao_Paulo">Brasilia Time<?php date_default_timezone_set("America/Sao_Paulo"); echo "   ".date('h:i');?></option>
              <option value="America/Godthab">America/Godthab<?php date_default_timezone_set("America/Godthab"); echo "   ".date('h:i');?></option>
              <option value="America/Miquelon">America/Miquelon<?php date_default_timezone_set("America/Miquelon"); echo "   ".date('h:i');?></option>
              <option value="America/Noronha">America/Noronha<?php date_default_timezone_set("America/Noronha"); echo "   ".date('h:i');?></option>
		  </optgroup>
		   <optgroup label="Africa">
              <option value="Africa/Lagos">West Africa Time<?php date_default_timezone_set("Africa/Lagos"); echo "   ".date('h:i');?></option>
              <option value="Africa/Cairo">Africa/Cairo<?php date_default_timezone_set("Africa/Cairo"); echo "   ".date('h:i');?></option>
              <option value="Africa/Lagos">Central Africa Time<?php date_default_timezone_set("Africa/Lagos"); echo "   ".date('h:i');?></option>
              <option value="Africa/Windhoek">Africa/Windhoek<?php date_default_timezone_set("Africa/Windhoek"); echo "   ".date('h:i');?></option>
		   </optgroup>
		    <optgroup label="Asia">
              <option value="Asia/Damascus">Syria, Jordan Time<?php date_default_timezone_set("Asia/Damascus"); echo "   ".date('h:i');?></option>
              <option value="Asia/Baghdad">Baghdad, East Africa Time<?php date_default_timezone_set("Asia/Baghdad"); echo "   ".date('h:i');?></option>
              <option value="Asia/Beirut">Jordan, Lebanon Time<?php date_default_timezone_set("Asia/Beirut"); echo "   ".date('h:i');?></option>
              <option value="Asia/Damascus">Asia/Damascus<?php date_default_timezone_set("Asia/Damascus"); echo "   ".date('h:i');?></option>
              <option value="Asia/Gaza">Asia/Gaza<?php date_default_timezone_set("Asia/Gaza"); echo "   ".date('h:i');?></option>
             <option value="Israel">Israel Time<?php date_default_timezone_set("Israel"); echo "   ".date('h:i');?></option>
              <option value="Asia/Baku">Asia/Baku<?php date_default_timezone_set("Asia/Baku"); echo "   ".date('h:i');?></option>
              <option value="Asia/Dubai">Dubai Time<?php date_default_timezone_set("Asia/Dubai"); echo "   ".date('h:i');?></option>
              <option value="Asia/Yerevan">Asia/Yerevan<?php date_default_timezone_set("Asia/Yerevan"); echo "   ".date('h:i');?></option>
              <option value="Asia/Kabul">Kabul Time<?php date_default_timezone_set("Asia/Yerevan"); echo "   ".date('h:i');?></option>
              <option value="Asia/Tehran">Tehran Time<?php date_default_timezone_set("Asia/Tehran"); echo "   ".date('h:i');?></option>
              <option value="Asia/Karachi">Pakistan, Maldives Time<?php date_default_timezone_set("Asia/Karachi"); echo "   ".date('h:i');?></option>
              <option value="Asia/Dhaka">Asia/Dhaka<?php date_default_timezone_set("Asia/Dhaka"); echo "   ".date('h:i');?></option>
              <option value="Asia/Kolkata">India, Sri Lanka Time<?php date_default_timezone_set("Asia/Kolkata"); echo "   ".date('h:i');?></option>
              <option value="Asia/Kathmandu">Kathmandu Time<?php date_default_timezone_set("Asia/Kathmandu"); echo "   ".date('h:i');?></option>
              <option value="Asia/Dhaka">Asia/Dhaka<?php date_default_timezone_set("Asia/Kolkata"); echo "   ".date('h:i');?></option>
              <option value="Asia/Omsk">Asia/Omsk<?php date_default_timezone_set("Asia/Omsk"); echo "   ".date('h:i');?></option>
              <option value="Asia/Rangoon">Asia/Rangoon<?php date_default_timezone_set("Asia/Rangoon"); echo "   ".date('h:i');?></option>
              <option value="Asia/Hong_Kong">Indochina Time<?php date_default_timezone_set("Asia/Hong_Kong"); echo "   ".date('h:i');?></option>
              <option value="Asia/Krasnoyarsk">Krasnoyarsk Time<?php date_default_timezone_set("Asia/Krasnoyarsk"); echo "   ".date('h:i');?></option>
              <option value="Asia/Irkutsk">Asia/Irkutsk<?php date_default_timezone_set("Asia/Irkutsk"); echo "   ".date('h:i');?></option>
              <option value="Asia/Singapore">China, Singapore, Perth<?php date_default_timezone_set("Asia/Singapore"); echo "   ".date('h:i');?></option>
              <option value="Asia/Tokyo">Japan, Korea Time<?php date_default_timezone_set("Asia/Tokyo"); echo "   ".date('h:i');?></option>
              <option value="Asia/Yakutsk">Asia/Yakutsk<?php date_default_timezone_set("Asia/Yakutsk"); echo "   ".date('h:i');?></option>
              <option value="Asia/Vladivostok">Asia/Vladivostok<?php date_default_timezone_set("Asia/Vladivostok"); echo "   ".date('h:i');?></option>
              <option value="Pacific/Midway">Pacific/Majuro<?php date_default_timezone_set("Pacific/Midway"); echo "   ".date('h:i');?></option>
			</optgroup>
			<optgroup label="Atlantic">
              <option option="Atlantic/Cape_Verde">Cape Verde Time<?php date_default_timezone_set("Atlantic/Cape_Verde"); echo "   ".date('h:i');?></option>
              <option option="Atlantic/Azores">Azores Time<?php date_default_timezone_set("Atlantic/Azores"); echo "   ".date('h:i');?></option>
			</optgroup>
			<optgroup label="Australia">
               <option value="Australia/Perth">Australia/Perth<?php date_default_timezone_set("Australia/Perth"); echo "   ".date('h:i');?></option>
               <option value="Australia/Eucla">Australia/Eucla<?php date_default_timezone_set("Australia/Eucla"); echo "   ".date('h:i');?></option>
               <option value="Australia/Adelaide">Adelaide Time<?php date_default_timezone_set("Australia/Adelaide"); echo "   ".date('h:i');?></li>
               <option value="Australia/Darwin">Australia/Darwin<?php date_default_timezone_set("Australia/Darwin"); echo "   ".date('h:i');?></option>
               <option value="Australia/Brisbane">Brisbane Time<?php date_default_timezone_set("Australia/Brisbane"); echo "   ".date('h:i');?></option>
               <option value="Australia/Sydney">Sydney, Melbourne Time<?php date_default_timezone_set("Australia/Sydney"); echo "   ".date('h:i');?></option>
               <option value="Australia/Lord_Howe">Australia/Lord Howe<?php date_default_timezone_set("Australia/Lord_Howe"); echo "   ".date('h:i');?></option>
			</optgroup>
			<optgroup label="UTC">
                  <option value="UTC/GMT">UTC Time<?php date_default_timezone_set("UTC/GMT"); echo "   ".date('h:i');?></option>
			</optgroup>
			<optgroup label="Europe">
                <option value="Europe/Lisbon">UK, Ireland, Lisbon Time<?php date_default_timezone_set("Europe/Lisbon"); echo "   ".date('h:i');?></option>
                <option value="Europe/London">Central European Time<?php date_default_timezone_set("Europe/London"); echo "   ".date('h:i');?></option>
                <option value="EET">Eastern European Time<?php date_default_timezone_set("EET"); echo "   ".date('h:i');?></option>
                <option value="Europe/Istanbul">Minsk Time<?php date_default_timezone_set("Europe/Istanbul"); echo "   ".date('h:i');?></option>
                <option value="Europe/Moscow">Moscow Time<?php date_default_timezone_set("Europe/Moscow"); echo "   ".date('h:i');?></option>
			</optgroup>
			<optgroup label="Pacific">
			   <option value="Pacific/Pago_Pago">Pacific/Pago Pago<?php date_default_timezone_set("Pacific/Pago_Pago"); echo "   ".date('h:i');?></option>
                 <option value="Pacific/Marquesas">Pacific/Marquesas<?php date_default_timezone_set("Pacific/Marquesas"); echo "   ".date('h:i');?></option>
                 <option value="Pacific/Gambier">Pacific/Gambier<?php date_default_timezone_set("Pacific/Gambier"); echo "   ".date('h:i');?></option>
                 <option value="Pacific/Pitcairn">Pacific/Pitcairn<?php date_default_timezone_set("Pacific/Pitcairn"); echo "   ".date('h:i');?></option>
                  <option value="Pacific/Easter">Pacific/Easter<?php date_default_timezone_set("Pacific/Easter"); echo "   ".date('h:i');?></option>
                  <option value="Pacific/Norfolk">Pacific/Norfolk<?php date_default_timezone_set("Pacific/Norfolk"); echo "   ".date('h:i');?></option>
                  <option value="Pacific/Noumea">Pacific/Noumea<?php date_default_timezone_set("Pacific/Noumea"); echo "   ".date('h:i');?></option>
                  <option value="Pacific/Auckland">Auckland Time<?php date_default_timezone_set("Pacific/Auckland"); echo "   ".date('h:i');?></option>
                  <option value="Pacific/Fiji">Pacific/Fiji<?php date_default_timezone_set("Pacific/Fiji"); echo "   ".date('h:i');?></option>
                  <option value="Pacific/Fiji">Pacific/Majuro<?php date_default_timezone_set("Pacific/Fiji"); echo "   ".date('h:i');?></option>
                  <option value="Pacific/Tarawa">Pacific/Tarawa<?php date_default_timezone_set("Pacific/Tarawa"); echo "   ".date('h:i');?></option>
                  <option value="Pacific/Chatham">Pacific/Chatham<?php date_default_timezone_set("Pacific/Chatham"); echo "   ".date('h:i');?></option>
                  <option value="Pacific/Apia">Pacific/Apia<?php date_default_timezone_set("Pacific/Apia"); echo "   ".date('h:i');?></option>
                  <option value="Pacific/Tongatapu">Pacific/Tongatapu<?php date_default_timezone_set("Pacific/Tongatapu"); echo "   ".date('h:i');?></option>
                  <option value="Pacific/Kiritimati">Pacific/Kiritimati<?php date_default_timezone_set("Pacific/Kiritimati"); echo "   ".date('h:i');?></option>
			</optgroup>
    </select>
   </div>