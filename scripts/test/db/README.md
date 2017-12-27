# Esquemas de banco de dados

## XML de exportação do *mysqldump*

Uma forma simples de se obter um arquivo xml com um esquema de banco de dados é exportando via mysqldump:

    mysqldump --xml -t -u [user] -p [database] > /path/to/file.xml

O XML gerado ficará semelhante ao abaixo:

    <?xml version="1.0"?>
    <mysqldump xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <database name="wpminc_unittest">
        <table_data name="wpminc_2_options">
            <row>
                <field name="option_id">1</field>
			    ...
            </row>
        </table>
    </database>

Para usar, deve-se utilizar na função de getDataSet um método específico do mysql, createMySQLXMLDataSet:

    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(dirname(__FILE__).'/db/base-mysqldump.xml');
    }

    

## XML Plano

Caso se opte por utilizar um arquivo XML plano, deve-se utilizar na função de getDataSet o método createFlatXMLDataSet()

    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(dirname(__FILE__).'/db/base-flat.xml');
    }

O XML deve ter o seguinte padrão:

    <?xml version="1.0"?>
    <dataset>
	    <table_data name="wpminc_2_options">
            <row>
		        <field name="option_id">1</field>
                ...
            </row>
	    </table:
    </dataset>


