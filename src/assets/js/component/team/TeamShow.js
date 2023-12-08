import {Datagrid, ReferenceArrayField, ReferenceField, Show, SimpleShowLayout, TextField, usePermissions} from "react-admin";

export const TeamShow = props => {
    const {permissions} = usePermissions();
    return <Show {...props}>
        <SimpleShowLayout>
            {permissions?.indexOf('ROLE_ADMIN') !== -1 &&
                <ReferenceField reference="users" source="user" link="show">
                    <TextField source="username"/>
                </ReferenceField>
            }
            <ReferenceField reference="factions" source="faction" link="show">
                <TextField source="name"/>
            </ReferenceField>
            <TextField source={"name"}/>
            <TextField source={"playType"}/>
            <TextField source={"playCategory"}/>
            <ReferenceArrayField label="Players" reference="players" source="players">
                <Datagrid bulkActionButtons={false}>
                    <TextField source="number" />
                    <TextField source="name" />
                    <TextField source="position.cost" />
                </Datagrid>
            </ReferenceArrayField>
        </SimpleShowLayout>
    </Show>
};