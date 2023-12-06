import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {
    BooleanField,
    ChipField, Datagrid,
    InfiniteList,
    NumberField,
    ReferenceArrayField,
    ReferenceField,
    ResourceContextProvider,
    SingleFieldList,
    TextField, useRecordContext
} from "react-admin";
import {Table, TableBody, TableCell, TableHead, TableRow} from "@mui/material";

export const PositionShowPanelHead = () => (
    <TableRow>
        <TableCell>Faction</TableCell>
        <TableCell>Quantity</TableCell>
        <TableCell>M</TableCell>
        <TableCell>F</TableCell>
        <TableCell>AG</TableCell>
        <TableCell>CP</TableCell>
        <TableCell>AR</TableCell>
        <TableCell>Skills</TableCell>
        <TableCell>Cost</TableCell>
        <TableCell>Primary Skills</TableCell>
        <TableCell>Secondary Skills</TableCell>
    </TableRow>
);
export const PositionShowPanelRow = () => (

    <TableRow>
        <TableCell>
            <ReferenceField reference="factions" source="faction" link="show">
                <TextField source="name"/>
            </ReferenceField>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"quantity"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"m"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"f"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"ag"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"cp"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"ar"}/>
        </TableCell>
        <TableCell>
            <ReferenceArrayField label="Skills" reference="skills" source="skills">
                <SingleFieldList linkType="show">
                    <ChipField source="name"/>
                </SingleFieldList>
            </ReferenceArrayField>
        </TableCell>
        <TableCell>
            <NumberField source={"cost"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"primarySkills"}/>
        </TableCell>
        <TableCell>
            <FieldGuesser source={"secondarySkills"}/>
        </TableCell>
    </TableRow>
);

export const PositionShowPanel = props => {
    const record = useRecordContext();

    return <>
        <Table>
            <TableHead>
                <PositionShowPanelHead/>
            </TableHead>
            <TableBody>
                <PositionShowPanelRow/>
            </TableBody>
        </Table>
        <ResourceContextProvider value="skills">
            <InfiniteList
                hasCreate={false}
                storeKey={false}
                resource="skills"
                filter={{ positions: [record.id] }}
                title=" "
                exporter={false}
            >
                <Datagrid rowClick="show" bulkActionButtons={false}>
                    <TextField source={"name"}/>
                    <BooleanField source={"mandatory"}/>
                    <TextField source={"description"}/>
                </Datagrid>
            </InfiniteList>
        </ResourceContextProvider>
    </>
};

export const PositionShow = props => (
    <ShowGuesser {...props}>
        <FieldGuesser source={"name"}/>
        <PositionShowPanel/>
    </ShowGuesser>
);
