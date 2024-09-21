<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #07072d; /* Midnight Blue */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            color: #333333;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777777;
        }
        .highlight {
            color: #F1D3CA; /* Blush Pink */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>RSVP Confirmation</h1>
        </div>
        <div class="content">
            <p>Dear <?php echo $guest_name; ?>,</p>
            <p>We are thrilled to inform you that your RSVP has been confirmed!</p>
            <p>As Daniel &amp; Cherrylyn embark on this exciting new chapter of their lives, they are overjoyed to have you with them to celebrate this special occasion.</p>
            <br/>
            <p>Here are some of the quick guide (what, when & where)</p>
            <ul>
                <li>What? Daniel &amp; Cherrylyn Wedding</li>
                <li>When? January 11, 2025 @ 2:00 PM PHT</li>
                <li>Where? <a href="https://maps.app.goo.gl/yWZbxifuyx813puR6">Kamay Ni Hesus Healing Church</a></li>
                <li>Outfit? Formal/Semi-formal</li>
                <li>Motif? Midnight Blue or Blush Pink</li>
            </ul>
            <br/>
            <p>We encourage you to have a quick glance to our <a href="https://dsciwedding.com">website</a> which contains full information.</p>
            <p>Thank you for being a part of their journey!</p>
            <p class="highlight">Looking forward to seeing you!</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 Daniel &amp; Cherrylyn. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
