from zeep import Client

test = {
    '_value_1': [
        {
            '_value_1': [
                1,
                'Mamadou Abass',
                'Diallo',
                'm.abassdiallo@gmail.com',
                {
                    '_value_1': [
                        'ROLE_ADMIN',
                        'ROLE_USER'
                    ],
                    'arrayType': 'xsd:string[2]',
                    'offset': None,
                    'id': None,
                    'href': None,
                    '_attr_1': {
                    }
                }
            ],
            'arrayType': 'xsd:ur-type[5]',
            'offset': None,
            'id': None,
            'href': None,
            '_attr_1': {
            }
        },
        {
            '_value_1': [
                2,
                'Mouhamadou',
                'Boly',
                'boly@gmail.com',
                {
                    '_value_1': [
                        'ROLE_USER'
                    ],
                    'arrayType': 'xsd:string[1]',
                    'offset': None,
                    'id': None,
                    'href': None,
                    '_attr_1': {
                    }
                }
            ],
            'arrayType': 'xsd:ur-type[5]',
            'offset': None,
            'id': None,
            'href': None,
            '_attr_1': {
            }
        }
    ],
    'arrayType': 'SOAP-ENC:Array[2]',
    'offset': None,
    'id': None,
    'href': None,
    '_attr_1': {
    }
}
mylist = list()
list1 = test["_value_1"][0]["_value_1"][0:4] + test["_value_1"][0]["_value_1"][4]["_value_1"]

list2 = test["_value_1"][1]["_value_1"][0:4] + test["_value_1"][1]["_value_1"][4]["_value_1"]

mylist.append(list1)
mylist.append(list2)

print(mylist)
email = "m.abassiallo@gmail.com"
for user in mylist:
    if user[3] == email:
        print("True")
