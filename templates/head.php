<head>
    <title><?php echo $this->page->getTitle(); ?></title>
    <style>
        nav table {
            width: 100%;
        }

        nav.navheader td.curr,
        nav.navheader th.curr {
            text-align: center;
        }

        nav.navheader td.prev,
        nav.navheader th.prev {
            width: 30%;
            text-align: left;
        }

        nav.navheader td.parent,
        nav.navheader th.parent {
            width: 40%;
            text-align: center;
        }

        nav.navheader td.next,
        nav.navheader th.next {
            text-align: right;
            width: 30%;
        }

        nav.navfooter td.prev,
        nav.navfooter th.prev {
            width: 30%;
            text-align: left;
        }

        nav.navfooter td.parent,
        nav.navfooter th.parent {
            width: 40%;
            text-align: center;
        }

        nav.navfooter td.next,
        nav.navfooter th.next {
            text-align: right;
            width: 30%;
        }
    </style>
</head>
