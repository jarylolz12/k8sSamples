### Changelog

All notable changes to this project will be documented in this file. Dates are displayed in UTC.

Created with [`Love`].

#### [Unreleased](https://github.com/Shifl-inc/Shifl-Admin-Panel/compare/v1.0.9...HEAD)

- Create shipment tabs in view, edit, and delete. Move the default fiel… [`#2`](https://github.com/Shifl-inc/Shifl-Admin-Panel/pull/2)
- Container size resource [`#3`](https://github.com/Shifl-inc/Shifl-Admin-Panel/pull/3)
- Added Supplier Resource and added relationships. [`#1`](https://github.com/Shifl-inc/Shifl-Admin-Panel/pull/1)
- Created corresponding Nova resources. [`4cd7ad8`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/4cd7ad8385a1af817c1f629424392a7ae1aa5ed3)
- Created migrations. [`c8709c3`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/c8709c347e95733380b6888e58d6b06397fd664a)
- Added migration file, model, and nova resource for Incoterms. [`4631664`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/4631664f8106f1453853a656e988d78346f309a4)
- Create shipment tabs in view, edit, and delete. Move the default fields in the Header Information tab. Give a sample field for Pre alert and Charges tab. [`b308f75`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/b308f751453afb9f660516ce62d34517403ba6d0)
- Added Supplier Resource and added relationships. [`195f199`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/195f19959c6f69c63036b53869b68fa98159b339)
- Migration file, model, and nova resource for container sizes. [`b22214b`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/b22214b2ff1f7bf57142de5c936b9f5246bb6d5a)
- Created Terminal Region Resource. [`8f06ba3`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/8f06ba336eb041351cb1ae4381128002453b9ed1)
- Created necessary Models. [`1502c0d`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/1502c0d63b4cfdedc089f2709a6f0acc56a99256)
- Added terminal regions, carrier. [`f569895`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/f5698951a881017b37517230fef9ae93e9bee370)
- Hide sample shipment tab fields from index. [`c7b43b4`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/c7b43b440b75e4743d46f2fd7fad514f91978612)
- Hide terminal region and carrier for the meantime as the nova tab package is displaying related resource in another tab in certain reltionship types. [`7432871`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/7432871d79a19831a3c13791b0ff501ec7eb39f0)

#### [1.0.9](https://github.com/Shifl-inc/Shifl-Admin-Panel/compare/v1.0.8...v1.0.9)

> 23 January 2020

- Added method checker. [`d1861ff`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/d1861ff0dbb01db53ec4a0b11c8392d86f3d46fe)
- Display shifl ref for shipments in global search. [`fe8fe2d`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/fe8fe2d1b264db7e2717467172479ead7c4115ba)

#### [1.0.8](https://github.com/Shifl-inc/Shifl-Admin-Panel/compare/v1.0.7...v1.0.8)

> 22 January 2020

- Fix Shipment filter functionalities. [`a1da918`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/a1da9181c7a729b69471ed18630c12dc83bf79b9)
- Fix shipments index issue. [`f1dc2ff`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/f1dc2ff20caf87a02ea47a248d2306b789050ac8)
- Change how shifl_ref of shipment resource is saved. [`3433b3d`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/3433b3dd141f241a5a3816e4ad6b075987cfc803)
- Retaing shifl_ref default value for old entries. [`7a108fe`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/7a108fe8cdad486ac8d75a5c845e3ab9a4d901db)

#### [1.0.7](https://github.com/Shifl-inc/Shifl-Admin-Panel/compare/v1.0.6...v1.0.7)

> 20 January 2020

- Fixed id issue showing in the customers table. [`b1f3bab`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/b1f3babbfd38174fb7689af30b68244579c8d9ef)
- Update sale_persons as nullable. [`cbbd254`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/cbbd2544b92a0c2d1730145a9eb82f3e1a6d52ba)
- Remove sale_persons field when there is no sale representative selected. [`450cdae`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/450cdae7e8e1270f69c30051e5ef4bad8092919d)
- Show only their customer's shipment for sales reps. Fix filter query for my shipments of managers. [`148a1bb`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/148a1bbc567084cd7b01639e8d9265738dc08317)
- Make etd as sortable. [`d1425b9`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/d1425b91b7dc3f5c9259beb0efb74b283fd9f754)
- Change labels for shipment resource. [`26026d9`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/26026d9b3e19a4eb8139add08c5c7070769b7f3f)
- Increased login expiration to 5 days. [`8bce87f`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/8bce87fa4f2e0e19a4b76e541166714153045f26)
- Changed customer label to Customer. [`73f09ed`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/73f09ed3b47ad9f6cbb4a6d7b891659ef1fc020d)
- Added cancelled shipment status under the filter. [`8499f90`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/8499f90b4ea4ebf747c0ca3edf707d7775f84bde)

#### [1.0.6](https://github.com/Shifl-inc/Shifl-Admin-Panel/compare/v1.0.5...v1.0.6)

> 16 January 2020

- Remove required rule for Sales Representatives in customer resource. [`b1bd4a4`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/b1bd4a46a334b10c4610f9e6d2a206d790b7738e)

#### [1.0.5](https://github.com/Shifl-inc/Shifl-Admin-Panel/compare/v1.0.4...v1.0.5)

> 16 January 2020

- Major updates: [`df9cd03`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/df9cd03fc1645e5e08824768992e3d2effe16019)
- Change sales person label to sales representative. [`d032731`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/d032731e173e67527b9d529db672d42a9cd02469)
- Fixed issue created by code formatting. Rename Sale Agent role to Sales Representative. [`8b2edc1`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/8b2edc1ce5f53fc52d571d880d21271812013e5b)
- Major refactor on the account manager selection option for customers resource. [`8c71f73`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/8c71f738b0bbd98591a373b2cb2bea401e115002)
- Added Select Here option and will be selected as default option if still on the process of customer creation. [`b6a977a`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/b6a977aedde0e704f51dad2054ff21d2e84e52a5)
- Check approved shipments that has departed using scheduler. [`2501be5`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/2501be57f721710891cb5b373ea6c6c76c377eb9)
- Fixed account manager required issue. [`27653ef`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/27653ef6c8db0e1114d2fd786a209c41a59a1593)
- Revert back the removal of required rules for company name, address, and phone number. [`f3ed498`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/f3ed4985e10eff0cd9eeb7dc763d0a2cbcd94798)

#### [1.0.4](https://github.com/Shifl-inc/Shifl-Admin-Panel/compare/v1.0.3...v1.0.4)

> 14 January 2020

- Remove required rules for customer address and phone. [`282f33f`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/282f33f2079842e34f36cba27291bf50c3541b88)

#### [1.0.3](https://github.com/Shifl-inc/Shifl-Admin-Panel/compare/v1.0.2...v1.0.3)

> 13 January 2020

- Created Account Manager Special Select field. [`0a8cc62`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/0a8cc623b28ab299b7691a5b667a7c757f574ed6)
- Added check routes for admin and account manager in order to show only the select account manager option to super admin only. [`9e331f0`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/9e331f0365e57ddff8269ef6e257e3323b3f8548)
- Commit new yarn file for sub package. Commit update composer lock file. [`dfa2f5c`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/dfa2f5c3c3034fb766cf255ab2b9cab0ec1f7dc2)
- Created new field for account manager selection. [`433e421`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/433e421d2b67f6b219c3d8ce414b486ae52f6199)
- Remove auto assignment in create customer. [`6cd5df7`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/6cd5df7eaeadc94b3cfd33420645d07bd9f5f1fd)
- Controller for handling for display of account managers, updating account manager, and deletion of account manager from customer. [`8c1429f`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/8c1429f0abbb448784f6d316fad35ffe3bc8dfce)
- Create relationship between customer and account manager. [`76ae981`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/76ae981a902bf9ed4dab3ca25fa26c0c93743054)
- Auto Fill Shifl Reference number. Makro for autofill. [`549196f`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/549196ff4a562ed454621155a31943a12000f837)
- Add necessary required rules when creating/updating a customer. [`552ab51`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/552ab51f0a024cad32752e0caa579d06fffae257)
- Modified the fields and add the new field for assigning account managers. [`491bfb3`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/491bfb337c840a1b5a01182ac410aa1b5f231285)
- Hide Account Manager from customers index list. [`f49933b`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/f49933bb71b6c6beb2242a43711ba493ec6286de)
- Let the account manager/admin view a customer resource. [`877ddac`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/877ddacb195b28eac2a157b55f507d65dd9555d1)
- Removed the view single resource for customer. [`9757773`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/975777355f2f58c5cd2e0648b432ec291b62410c)

#### [1.0.2](https://github.com/Shifl-inc/Shifl-Admin-Panel/compare/v1.0.1...v1.0.2)

> 10 January 2020

- Unassigned filter for shipments. My shipments default filter. [`cd47665`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/cd47665169d4d23b5cffff38465f2d95d8194cf4)

#### [1.0.1](https://github.com/Shifl-inc/Shifl-Admin-Panel/compare/v1.0.0...v1.0.1)

> 8 January 2020

- Remove conflicting search term for customers in shipment resource. [`8383626`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/8383626f315f265b3cf42e520bce3d9b1545ab6c)
- Added search-relation for nova vendor. [`a906b14`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/a906b1434003f075bd836d92bab32e9acf7aafa2)
- Added filter for the shipments list. [`3e5e594`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/3e5e594767da5a9b05c0ca9a3f0a560313a9790f)
- Add completed status and fixed In Transit status text. [`205e6fc`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/205e6fc50e8a4738757b74ed4c92ff86f6b4d001)
- Allow account manager to add customer and then when the account manager is creating a customer, automatically assign that customer to him/her. [`e68b809`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/e68b8098897101197ad2de676c5f5f69a5c3733c)
- Backup logo image. [`66d2942`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/66d2942e254b1ed30cc3b8c8101a5d26dc8085df)
- Added SearchRelation trait for shipment to enable the search for customers. [`14d1873`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/14d187360de9d64a7d7c544d0eea41d1af326ca1)
- Remove gravatars from user resources views. Remove gravatar from header user profile. [`84c6bcf`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/84c6bcf30062e2ea479982f38a917a8fa4f0a22c)
- Change date format in index list. [`307c8e4`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/307c8e49f3e31fc74753297dd3c78ebe937bfc54)
- Remove date fields in search fields array as they have conflicts with text searches. [`29af00c`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/29af00c9bd04ae82afb43e087c8a097ff2b081a7)
- Replace default title with Shifl. [`1ff3588`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/1ff3588b072cfc08fc944f3458d9ed33c547ef82)
- More searchabe fields for shipment resource. [`c55b67b`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/c55b67b2e2a987629d1e7c3a27dbb87622729de2)
- Add pending refund to shipment statuses. [`858865d`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/858865d90f90d056667c2518108774ad32dd98d1)
- Add more searchable fields for customer resource. [`286b377`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/286b377180b51e97a604692507869332db002715)
- Change text of shipment filter from Me to My shipments. [`728f2e5`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/728f2e5b14b9da563dfc44004e76cd23df7b3812)
- Fix 403 error for the superadmin user. Set environment to local. [`41af500`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/41af50096d7801cff0f46a7b04a5b2cb59ff0b04)

#### 1.0.0

> 6 January 2020

- Initial commit. [`fed797f`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/fed797fd09e70e3c59e75e681198c61e3b7974be)
- Created Roles and Permissions new seeds. [`78213f6`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/78213f60f5019ce9b9398b3ba703b1d5ec657cc6)
- Commit yarn lock file from staging. [`260c000`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/260c000cc5d7b44171b5bbcedd831bae3b26f0bc)
- Install Laravel Nova. Remove conflicting migration files. [`117184f`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/117184f58d264fc5709d0438dfa0829f9ac9e46e)
- Created Account Manager, Admin nova resources as well as their corresponding models. [`b4854dc`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/b4854dcb291d80a9e6aab801e362763243107db1)
- Add Customer and Shipment resources to Nova Admin. [`de70d9b`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/de70d9b2673b708b81c91e9621fb50b9534160eb)
- Add superadmin user to access gate on non local environments. [`22ce6cd`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/22ce6cdf2d255a8033c202e4ec1c1bc6e8ed9ed7)
- Comment out fields for shipments. Added etd, eta, and custom notes fileds in shipments. Make default sorting to eta. Added dropdows for shipment statuses. [`3db75a3`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/3db75a34f88e59f3cddfbc14ad4fdb8c3eacc0d8)
- Add filtering for shipment status. [`9bc5016`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/9bc5016b3e8ea6a68fed89bc7d8de211b260c8ac)
- Filter the shipment resource. [`1cd7f90`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/1cd7f9012c12b43181215cfd5d68890f8e940aca)
- Add user database seeder. [`de9607a`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/de9607a3d8e9f89e42d008fb222369216a9ec948)
- Create LICENSE [`7b9316f`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/7b9316f6abad424da4470e76b59572958cf3137f)
- Additional customer and shipment fields. [`bbd010c`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/bbd010c925c0dadadcdf5aff46ec318ab87a515e)
- Remove id from index list page of shipment, user, and customer resources. [`4e05f81`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/4e05f81491e6d0bcd1645ea88de561251b890fbc)
- Logout users if authentication failed in Nova to avoid getting stuck on forbidden page. [`e0d833d`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/e0d833ddd61e99d75340a6a8e5578974d1cf7c88)
- Give gate pass for initial Nova admin users for non local environment. [`e32936f`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/e32936f2eb790d58bdbac31bf9f7c8185526ff23)
- Enable run user database table seeder. [`44e9c1c`](https://github.com/Shifl-inc/Shifl-Admin-Panel/commit/44e9c1c09a947786eaac5fa7a391b44c1f3c0cb2)
