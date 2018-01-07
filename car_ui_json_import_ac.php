<?PHP
if(!defined("CONFIG")) exit();
if(!isset($login)) { show_error("You do not have administrator rights\n"); return; } ?>

<script type="text/javascript">
var
    charfield = document.getElementById('charsleft'),
    messagefield = document.getElementById('nachricht'),
    maxlength = messagefield.getAttribute('maxlength');
function showCharsLeft() {
    var charsRemaining = maxlength - messagefield.value.length;
    if(charsRemaining < 0) {
        messagefield.value = messagefield.value.substr(0, 512);
        charsRemaining = 0;
    }
    charfield.value = charsRemaining;
}
</script>
<?PHP
$file = $_POST['json_file'];
$json = json_decode($file, true);
$name = $json["name"];
$brand = $json["brand"];
$horsepower = $json["specs"]["bhp"];
$torque = $json["specs"]["torque"];
$weight = $json["specs"]["weight"];
$description = $json["description"];
?>
<form action=".?page=car_add_do" method="post">
		<table border="0" cellspacing="0" cellpadding="1" width="100%">
		<tr>
      <td width="100">Sim:</td>
    	<td>
    			<select name="sim">
    					<option value="ac">Assetto Corsa</option>
    			</select>
    	  </td>
    	</tr>
			<tr><td>Brand:</td>
        <td><input type="text" name="brand" value= "<?php echo $brand;?>" size="20" maxlength="20"></td></tr>
			<tr><td>Name:</td>
        <td><input type="text" name="name" value= "<?php echo $name;?>" size="20" maxlength="20"></td></tr>
      <tr>
        	<td width="100">Simcode:</td>
        	<td>
        		<select name="code">
        			<optgroup label="Assetto Corsa">
        			<option disabled>────────────────</option>
        			<option value="abarth500">abarth500</option>
        			<option value="abarth500_s1">abarth500_s1</option>
        			<option value="alfa_romeo_giulietta_qv">alfa_romeo_giulietta_qv</option>
        			<option value="alfa_romeo_giulietta_qv_le">alfa_romeo_giulietta_qv_le</option>
        			<option value="bmw_1m">bmw_1m</option>
        			<option value="bmw_1m_s3">bmw_1m_s3</option>
        			<option value="bmw_m3_e30">bmw_m3_e30</option>
        			<option value="bmw_m3_e30_drift">bmw_m3_e30_drift</option>
        			<option value="bmw_m3_e30_dtm">bmw_m3_e30_dtm</option>
        			<option value="bmw_m3_e30_gra">bmw_m3_e30_gra</option>
        			<option value="bmw_m3_e30_s1">bmw_m3_e30_s1</option>
        			<option value="bmw_m3_e92">bmw_m3_e92</option>
        			<option value="bmw_m3_e92_drift">bmw_m3_e92_drift</option>
        			<option value="bmw_m3_e92_s1">bmw_m3_e92_s1</option>
        			<option value="bmw_m3_gt2">bmw_m3_gt2</option>
        			<option value="bmw_z4">bmw_z4</option>
        			<option value="bmw_z4_drift">bmw_z4_drift</option>
        			<option value="bmw_z4_gt3">bmw_z4_gt3</option>
        			<option value="bmw_z4_s1">bmw_z4_s1</option>
        			<option value="bo_caterham_academy_lhd">bo_caterham_academy_lhd</option>
        			<option value="corvette_dp_c">corvette_dp_c</option>
        			<option value="corvette_dp_d">corvette_dp_d</option>
        			<option value="ferrari_312t">ferrari_312t</option>
        			<option value="ferrari_458">ferrari_458</option>
        			<option value="ferrari_458_gt2">ferrari_458_gt2</option>
        			<option value="ferrari_458_s3">ferrari_458_s3</option>
        			<option value="ferrari_599xxevo">ferrari_599xxevo</option>
        			<option value="ferrari_f40">ferrari_f40</option>
        			<option value="ferrari_f40_s3">ferrari_f40_s3</option>
        			<option value="ferrari_laferrari">ferrari_laferrari</option>
        			<option value="ks_abarth500_assetto_corse">ks_abarth500_assetto_corse</option>
        			<option value="ks_abarth_595ss">ks_abarth_595ss</option>
        			<option value="ks_abarth_595ss_s1">ks_abarth_595ss_s1</option>
        			<option value="ks_abarth_595ss_s2">ks_abarth_595ss_s2</option>
        			<option value="ks_alfa_33_stradale">ks_alfa_33_stradale</option>
        			<option value="ks_alfa_giulia_qv">ks_alfa_giulia_qv</option>
        			<option value="ks_alfa_mito_qv">ks_alfa_mito_qv</option>
        			<option value="ks_alfa_romeo_155_v6">ks_alfa_romeo_155_v6</option>
        			<option value="ks_alfa_romeo_4c">ks_alfa_romeo_4c</option>
        			<option value="ks_alfa_romeo_gta">ks_alfa_romeo_gta</option>
        			<option value="ks_audi_a1s1">ks_audi_a1s1</option>
        			<option value="ks_audi_r18_etron_quattro">ks_audi_r18_etron_quattro</option>
        			<option value="ks_audi_r8_lms">ks_audi_r8_lms</option>
        			<option value="ks_audi_r8_lms_2016">ks_audi_r8_lms_2016</option>
        			<option value="ks_audi_r8_plus">ks_audi_r8_plus</option>
        			<option value="ks_audi_sport_quattro">ks_audi_sport_quattro</option>
        			<option value="ks_audi_sport_quattro_rally">ks_audi_sport_quattro_rally</option>
        			<option value="ks_audi_sport_quattro_s1">ks_audi_sport_quattro_s1</option>
        			<option value="ks_audi_tt_cup">ks_audi_tt_cup</option>
        			<option value="ks_audi_tt_vln">ks_audi_tt_vln</option>
        			<option value="ks_bmw_m235i_racing">ks_bmw_m235i_racing</option>
        			<option value="ks_bmw_m4">ks_bmw_m4</option>
        			<option value="ks_bmw_m4_akrapovic">ks_bmw_m4_akrapovic</option>
        			<option value="ks_corvette_c7r">ks_corvette_c7r</option>
        			<option value="ks_corvette_c7_stingray">ks_corvette_c7_stingray</option>
        			<option value="ks_ferrari_488_gt3">ks_ferrari_488_gt3</option>
        			<option value="ks_ferrari_488_gtb">ks_ferrari_488_gtb</option>
        			<option value="ks_ferrari_f138">ks_ferrari_f138</option>
        			<option value="ks_ferrari_fxx_k">ks_ferrari_fxx_k</option>
        			<option value="ks_ferrari_sf15t">ks_ferrari_sf15t</option>
        			<option value="ks_ford_escort_mk1">ks_ford_escort_mk1</option>
        			<option value="ks_ford_gt40">ks_ford_gt40</option>
        			<option value="ks_ford_mustang_2015">ks_ford_mustang_2015</option>
        			<option value="ks_glickenhaus_scg003">ks_glickenhaus_scg003</option>
        			<option value="ks_lamborghini_aventador_sv">ks_lamborghini_aventador_sv</option>
        			<option value="ks_lamborghini_countach">ks_lamborghini_countach</option>
        			<option value="ks_lamborghini_countach_s1">ks_lamborghini_countach_s1</option>
        			<option value="ks_lamborghini_gallardo_sl">ks_lamborghini_gallardo_sl</option>
        			<option value="ks_lamborghini_gallardo_sl_s3">ks_lamborghini_gallardo_sl_s3</option>
        			<option value="ks_lamborghini_huracan_gt3">ks_lamborghini_huracan_gt3</option>
        			<option value="ks_lamborghini_huracan_performante">ks_lamborghini_huracan_performante</option>
        			<option value="ks_lamborghini_huracan_st">ks_lamborghini_huracan_st</option>
        			<option value="ks_lamborghini_miura_sv">ks_lamborghini_miura_sv</option>
        			<option value="ks_lamborghini_sesto_elemento">ks_lamborghini_sesto_elemento</option>
        			<option value="ks_lotus_25">ks_lotus_25</option>
        			<option value="ks_lotus_3_eleven">ks_lotus_3_eleven</option>
        			<option value="ks_lotus_72d">ks_lotus_72d</option>
        			<option value="ks_maserati_250f_12cyl">ks_maserati_250f_12cyl</option>
        			<option value="ks_maserati_250f_6cyl">ks_maserati_250f_6cyl</option>
        			<option value="ks_maserati_alfieri">ks_maserati_alfieri</option>
        			<option value="ks_maserati_gt_mc_gt4">ks_maserati_gt_mc_gt4</option>
        			<option value="ks_maserati_levante">ks_maserati_levante</option>
        			<option value="ks_maserati_mc12_gt1">ks_maserati_mc12_gt1</option>
        			<option value="ks_maserati_quattroporte">ks_maserati_quattroporte</option>
        			<option value="ks_mazda_787b">ks_mazda_787b</option>
        			<option value="ks_mazda_miata">ks_mazda_miata</option>
        			<option value="ks_mazda_mx5_cup">ks_mazda_mx5_cup</option>
        			<option value="ks_mazda_mx5_nd">ks_mazda_mx5_nd</option>
        			<option value="ks_mazda_rx7_spirit_r">ks_mazda_rx7_spirit_r</option>
        			<option value="ks_mazda_rx7_tuned">ks_mazda_rx7_tuned</option>
        			<option value="ks_mclaren_570s">ks_mclaren_570s</option>
        			<option value="ks_mclaren_650_gt3">ks_mclaren_650_gt3</option>
        			<option value="ks_mclaren_f1_gtr">ks_mclaren_f1_gtr</option>
        			<option value="ks_mclaren_p1">ks_mclaren_p1</option>
        			<option value="ks_mclaren_p1_gtr">ks_mclaren_p1_gtr</option>
        			<option value="ks_mercedes_190_evo2">ks_mercedes_190_evo2</option>
        			<option value="ks_mercedes_amg_gt3">ks_mercedes_amg_gt3</option>
        			<option value="ks_mercedes_c9">ks_mercedes_c9</option>
        			<option value="ks_nissan_370z">ks_nissan_370z</option>
        			<option value="ks_nissan_gtr">ks_nissan_gtr</option>
        			<option value="ks_nissan_gtr_gt3">ks_nissan_gtr_gt3</option>
        			<option value="ks_nissan_skyline_r34">ks_nissan_skyline_r34</option>
        			<option value="ks_pagani_huayra_bc">ks_pagani_huayra_bc</option>
        			<option value="ks_porsche_718_boxster_s">ks_porsche_718_boxster_s</option>
        			<option value="ks_porsche_718_boxster_s_pdk">ks_porsche_718_boxster_s_pdk</option>
        			<option value="ks_porsche_718_cayman_s">ks_porsche_718_cayman_s</option>
        			<option value="ks_porsche_718_spyder_rs">ks_porsche_718_spyder_rs</option>
        			<option value="ks_porsche_908_lh">ks_porsche_908_lh</option>
        			<option value="ks_porsche_911_carrera_rsr">ks_porsche_911_carrera_rsr</option>
        			<option value="ks_porsche_911_gt1">ks_porsche_911_gt1</option>
        			<option value="ks_porsche_911_gt3_cup_2017">ks_porsche_911_gt3_cup_2017</option>
        			<option value="ks_porsche_911_gt3_r_2016">ks_porsche_911_gt3_r_2016</option>
        			<option value="ks_porsche_911_gt3_rs">ks_porsche_911_gt3_rs</option>
        			<option value="ks_porsche_911_r">ks_porsche_911_r</option>
        			<option value="ks_porsche_911_rsr_2017">ks_porsche_911_rsr_2017</option>
        			<option value="ks_porsche_917_30">ks_porsche_917_30</option>
        			<option value="ks_porsche_917_k">ks_porsche_917_k</option>
        			<option value="ks_porsche_918_spyder">ks_porsche_918_spyder</option>
        			<option value="ks_porsche_919_hybrid_2015">ks_porsche_919_hybrid_2015</option>
        			<option value="ks_porsche_919_hybrid_2016">ks_porsche_919_hybrid_2016</option>
        			<option value="ks_porsche_935_78_moby_dick">ks_porsche_935_78_moby_dick</option>
        			<option value="ks_porsche_962c_longtail">ks_porsche_962c_longtail</option>
        			<option value="ks_porsche_962c_shorttail">ks_porsche_962c_shorttail</option>
        			<option value="ks_porsche_991_carrera_s">ks_porsche_991_carrera_s</option>
        			<option value="ks_porsche_991_turbo_s">ks_porsche_991_turbo_s</option>
        			<option value="ks_porsche_cayenne">ks_porsche_cayenne</option>
        			<option value="ks_porsche_cayman_gt4_clubsport">ks_porsche_cayman_gt4_clubsport</option>
        			<option value="ks_porsche_cayman_gt4_std">ks_porsche_cayman_gt4_std</option>
        			<option value="ks_porsche_macan">ks_porsche_macan</option>
        			<option value="ks_porsche_panamera">ks_porsche_panamera</option>
        			<option value="ks_praga_r1">ks_praga_r1</option>
        			<option value="ks_ruf_rt12r">ks_ruf_rt12r</option>
        			<option value="ks_ruf_rt12r_awd">ks_ruf_rt12r_awd</option>
        			<option value="ks_toyota_ae86">ks_toyota_ae86</option>
        			<option value="ks_toyota_ae86_drift">ks_toyota_ae86_drift</option>
        			<option value="ks_toyota_ae86_tuned">ks_toyota_ae86_tuned</option>
        			<option value="ks_toyota_celica_st185">ks_toyota_celica_st185</option>
        			<option value="ks_toyota_gt86">ks_toyota_gt86</option>
        			<option value="ks_toyota_supra_mkiv">ks_toyota_supra_mkiv</option>
        			<option value="ks_toyota_supra_mkiv_drift">ks_toyota_supra_mkiv_drift</option>
        			<option value="ks_toyota_supra_mkiv_tuned">ks_toyota_supra_mkiv_tuned</option>
        			<option value="ks_toyota_ts040">ks_toyota_ts040</option>
        			<option value="ktm_xbow_r">ktm_xbow_r</option>
        			<option value="lmp2_porsche_rs_spyder_evo">lmp2_porsche_rs_spyder_evo</option>
        			<option value="lotus_2_eleven">lotus_2_eleven</option>
        			<option value="lotus_2_eleven_gt4">lotus_2_eleven_gt4</option>
        			<option value="lotus_49">lotus_49</option>
        			<option value="lotus_98t">lotus_98t</option>
        			<option value="lotus_elise_sc">lotus_elise_sc</option>
        			<option value="lotus_elise_sc_s1">lotus_elise_sc_s1</option>
        			<option value="lotus_elise_sc_s2">lotus_elise_sc_s2</option>
        			<option value="lotus_evora_gtc">lotus_evora_gtc</option>
        			<option value="lotus_evora_gte">lotus_evora_gte</option>
        			<option value="lotus_evora_gte_carbon">lotus_evora_gte_carbon</option>
        			<option value="lotus_evora_gx">lotus_evora_gx</option>
        			<option value="lotus_evora_s">lotus_evora_s</option>
        			<option value="lotus_evora_s_s2">lotus_evora_s_s2</option>
        			<option value="lotus_exige_240">lotus_exige_240</option>
        			<option value="lotus_exige_240_s3">lotus_exige_240_s3</option>
        			<option value="lotus_exige_s">lotus_exige_s</option>
        			<option value="lotus_exige_scura">lotus_exige_scura</option>
        			<option value="lotus_exige_s_roadster">lotus_exige_s_roadster</option>
        			<option value="lotus_exige_v6_cup">lotus_exige_v6_cup</option>
        			<option value="lotus_exos_125">lotus_exos_125</option>
        			<option value="lotus_exos_125_s1">lotus_exos_125_s1</option>
        			<option value="mclaren_mp412c">mclaren_mp412c</option>
        			<option value="mclaren_mp412c_gt3">mclaren_mp412c_gt3</option>
        			<option value="mercedes_sls">mercedes_sls</option>
        			<option value="mercedes_sls_gt3">mercedes_sls_gt3</option>
        			<option value="p4-5_2011">p4-5_2011</option>
        			<option value="pagani_huayra">pagani_huayra</option>
        			<option value="pagani_zonda_r">pagani_zonda_r</option>
        			<option value="porsche_rs_spyder_evo">porsche_rs_spyder_evo</option>
        			<option value="ruf_yellowbird">ruf_yellowbird</option>
        			<option value="shelby_cobra_427sc">shelby_cobra_427sc</option>
        			<option value="supercars_ford">supercars_ford</option>
        			<option value="supercars_holden">supercars_holden</option>
        			<option value="supercars_nissan">supercars_nissan</option>
        			<option value="tatuusfa1">tatuusfa1</option>
        		<optgroup label="Life for Speed">
        			<option disabled>────────────────</option>
        		</optgroup>
        		<optgroup label="rFactor">
        			<option disabled>────────────────</option>
        		</optgroup>
        		<optgroup label="rFactor2">
        			<option disabled>────────────────</option>
        		</optgroup>
        	</select>
        	</td>
        </tr>
        <tr>
        	  <td width="120">Badge:</td>
        		<td>
        			<select name="badge">
        					<optgroup label="(images in /images/badges/)">
        					</optgroup>
        					<option value="" selected="selected"></option>
        		  <?php
        		       foreach(glob(dirname(__FILE__) . '/images/badges/*') as $filename){
        		       $filename = basename($filename);
        		       echo "<option value='" . $filename . "'>".$filename."</option>";
        		    }
              ?>
            </select>
        	  </td>
       </tr>
  			<tr><td>Horsepower</td>
          <td><input type="text" name="horsepower" value= "<?php echo $horsepower;?>" size="20" maxlength="20"></td></tr>
  			<tr><td>Torque:</td>
          <td><input type="text" name="torque" value= "<?php echo $torque;?>" min=1 max="9999"></td></tr>
  			<tr><td>Weight:</td>
          <td><input type="text" name="weight" value= "<?php echo $weight;?>" min=1 max="9999"></td></tr>
  		</table>
  		<table class="w3-table-all" border="0" cellspacing="0" cellpadding="1" width="100%">
        <tr>
        	<td width="120">Description (max. 2048 characters):</td>
        	<td><textarea cols="60" rows="10" maxlength="2048" name="description"><?PHP echo "$description";?></textarea></td>
        </tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" class="button submit" value="Save Car"/>
					<input type="button" class="button cancel" value="Cancel" onclick="history.go(-1);"/>
				</td>
			</tr>
		</table>
</form>
