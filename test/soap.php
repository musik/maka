<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=GBK');
function pr($arr,$exit=false){
  if ($arr === false){
    echo 'false';
  }else{
    printf('<pre>%s</pre>',var_export($arr,true));
  }
  if($exit) exit();
}
function output($str='') 
{
 echo $str."\n";
}
echo md5('123456');
echo "����";
$client = new soapclient("http://localhost:2999/api/wsdl?",array('encoding'=>'gbk'));
$client = new soapclient("http://ws.ynlp.com/api/wsdl?",array('encoding'=>'gbk'));

//output();
//output("Available actions:");
pr($client->__getFunctions());
//output();
//output();

//$data = file_get_contents("/home/muzik/as3/setter/db/test/company_add_instance.xml");
    $data = "<XMLData><cpID>68416</cpID><cpName>����ʡ��ͨ��¯���޹�˾</cpName><cpShortName>��ͨ��¯</cpShortName><cpIndustry>116</cpIndustry>   <cpAddressID>838</cpAddressID><cpAddress>����ʡ�ܿ���̫���ع�ҵ��</cpAddress><cpPost>461400</cpPost><cpTel>0394-6776179</cpTel><cpFax>0394-6776179</cpFax><cpSite>http://www.4000588865.com</cpSite><cpEmail>939153452@qq.com</cpEmail><cpAbout>&lt;p&gt;&amp;nbsp; ������ͨ��¯���޹�˾�ǹ��Ҷ�������B����¯������ҵ,������ͨ��ҵ��¯��ҵ԰����311���������̸��ٹ�·,��ͨ����,������Զ.���Ϻ�����ͨ��ҵ��¯ӵ�����Ƶ��ʱ���ϵ���Ƚ�����������;��&lt;/p&gt;\n&lt;p&gt;ѧ�Ͻ��Ĺ���ģʽ����������ǰ,����,�ۺ����;����ε���ҵ����͵��ŵ���ҵ�Ļ�.��˾��ҵ���ν:�ɼ��Ȼ,�пڽԱ�.Ҳ����'��ͨ��¯�����,��ȫ��ů������'&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; ���Ϻ�����ͨ��ҵ��¯��Ʒ��Ҫ��������¯��ȼú��¯��ȼ�͹�¯��ȼ����¯����ˮ��¯������������¯����ѹ��������̫����¯��ú������¯�����ܻ����Ͱ�ú��������¯��ȼú������¯��&lt;/p&gt;\n&lt;p&gt;WNSϵ����ʽȼ��(��)��ѹ��ˮ��¯�� ȼ��������¯��ú��ר�ù�¯����ʽ������¯�����ܻ�����¯��ȫ�Զ�ȼ��(��)��ʽ��ѹ��ˮ��¯���л�������¯����������¯���ߡ��С���&lt;/p&gt;\n&lt;p&gt;���ȷ�¯��13��ϵ��100�������ͺš�&lt;/p&gt;\n&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; ������ͨ��ҵ��¯ӵ���ۺ�Ŀ���ʵ��,רҵ�Ŀ�������ʦ����CAD�������,�������ͺͿ��ٳ��ͼ���,ʹ�ù�ѧ��άɨ���Ǵ������ݲɼ�����,Ӧ�ø�����Ʒ�������,��ӵ�����Ƶļ���������&lt;/p&gt;\n&lt;p&gt;�豸���ֶ�,��������켰ÿ���������˳������ǰ�ؿƼ������޷��.Ϊ���Ͽ�����Ӧ�г��ĸ��²�Ʒ�ṩ�˸�����֤. &amp;nbsp;&lt;/p&gt;\n&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;������ͨ��ҵ��¯����ּ&amp;ldquo;��������ҵ���������&amp;rdquo;�ķ�չĿ�꣬����&amp;ldquo;Ʒ��Ϊ�����������µ���ҵ���ȫ�����칩�Ⱦ�Ʒ����Ϊ�ͻ���������ֵ���ṩȫ���̡�ȫ��λ��ȫ���ķ�����&lt;/p&gt;\n&lt;p&gt;Ϊ��˾�ĳ��ʹ����&lt;/p&gt;\n&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;���Ϻ�����ͨ��ҵ��¯��ֿƼ�Ϊ�ȵ�,�˲�Ϊ֧��,����Ϊ��֤,���񲻶ϳ�ǰ����ּ,����ѭ'����һ̨��¯,��һλ����,��һ��ʾ������,����һƬ�г�'�ľ�Ӫ����,24Сʱ��ר��ֵ�ص绰��&lt;/p&gt;\n&lt;p&gt;�����������,��ʱ�ṩ���ϵİ���.����Ӯ�ø����г��ݶ�û�������,Ϊ'������ͨ��ҵ��¯'������ʻ��춨�˻���. ������ͨ��¯���޹�˾�ٷ���վ��www.4000588865.com ��ϵ��ʽ�� 13526295265 QQ��939153452&lt;/p&gt;</cpAbout><cpScore>1</cpScore><cpType>1</cpType><cpOpDate>2013/5/11 12:00:22</cpOpDate><cpUnionEndDate>2013/5/11 12:01:28</cpUnionEndDate><cpState>1</cpState><cpMember>������</cpMember><cpMasterBreed>��ͨ��¯</cpMasterBreed><cpMasterProduct>ȼ����¯|������¯|ȼú��¯|�����չ�¯</cpMasterProduct><cpCorporation>��Ӫ��˾</cpCorporation><cpMode>4</cpMode> <cpPhone>13526295265</cpPhone> <cpLogo>http://img1.dns4.cn/pic/68416/20130511111515_7628.jpg</cpLogo><cpBanner>http://c1.dns4.cn/images/backstage-logo.jpg</cpBanner><cpAboutPic>http://img1.dns4.cn/pic/68416/20130511113124_0721.jpg</cpAboutPic></XMLData>";
//$data = iconv('UTF-8','GB2312',$data);
  echo $data;
$result = $client->CompanyAdd(123456,$data);
pr($result);
//$result = $client->company_details(99988);
//pr($result);

//$result = $client->emptiness("");
//output("Running emptiness({a: ''})");
//var_export($result);
//output();
//output();

//$result = $client->complex_return();
//output("Running complex_return()");
//var_export($result);
//output();
//output();

?>
