<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختيار مشروع PFE</title>
    <style>
        body {
            font-family: Tahoma, sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
        }
        main {
            padding: 20px;
        }
        .notification {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>اختيار مشروع التخرج (PFE)</h1>
    </header>
    <main>
        <div class="notification">
            <p>عزيزي الطالب،</p>
            <p>
                تم اختيارك كزميل عمل (Binôme) في مشروع التخرج التالي:
            </p>
            <ul>
                <li><strong>عنوان المشروع:</strong> تطوير نظام إدارة المحتوى</li>
                <li><strong>المشرف:</strong> د. أحمد العلوي</li>
            </ul>
            <p>يرجى النقر على الزر أدناه لتأكيد موافقتك على العمل مع زميلك في هذا المشروع.</p>
            <button onclick="confirmBinome()">أوافق على العمل</button>
        </div>
    </main>
    <script>
        function confirmBinome() {
            alert("تم تأكيد موافقتك للعمل مع زميلك.");
        }
    </script>
</body>
</html>
