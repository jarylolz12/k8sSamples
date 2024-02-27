<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <title>Shipment Documents</title>
    </head>

    <body style="background: #eaebf0 ;color: #004d77 ;">
        <div style="max-width:1140px; margin: 0 auto;">
            <div>

                <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                    Submitted Documents!<br><br>
                    Please note, Documents have been submitted for <b> Shipment #{{$content['shifl_ref']}}</b><br><br>

                    Please input the documents into the correct place in our system.
                    <ul>
                        <?php if (count($content['documents']) > 0) : ?>
                        <?php foreach($content['documents'] as $document) : ?>
                        <li>The Document <b>{{ $document['path_name'] }}</b> is {{($document['type']!=='other') ? 'a' : 'an'}}
                            <?php if ($document['type']=='other') : ?>
                            <b>
                                other
                            </b>
                            type of document
                            <?php endif; ?>
                            <?php if ($document['type']!=='other') : ?>
                            <b>
                                {{
                                    $document['type']
                                }}
                            </b>
                            type of document
                            <?php endif; ?>
                            <?php if ($document['type']!=='other') : ?>and it is for their suppliers:
                            <?php foreach($document->suppliers as $supplier) : ?>
                                <div><b>{{ \App\Supplier::find($supplier)->company_name }}</b></div>
                            <?php endforeach; ?>
                            <?php endif; ?></li>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                    Please note the following!
                    <ul>
                        <li>Double-check each document that you upload before uploading it to ensure that everything is correct.</li> 
                        <li>If you receive a document called Other - open it to identify what it is.</li> 
                        <li>If you have already sent Customs - double check to see if these are additional documents or if the customer is just submitting the same ones you already have.</li>
                    </ul>
                </div>
            </div>
        </div>
    </body>

</html>
